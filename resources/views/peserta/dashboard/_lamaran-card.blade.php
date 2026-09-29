{{--
    Single "lamaran" (application) card row + its detail modal.
    Included by resources/views/peserta/dashboard/_lamaran-list.blade.php inside a loop.

    Expects:
      - $app     : application model
      - $badges  : array map of display_status => badge border/bg/text classes
--}}
@php
    $appStatus = $app->status instanceof \App\Enums\ApplicationStatus ? $app->status->value : $app->status;
    $cardAccent = match ($appStatus) {
        'diterima' => 'bg-teal-50/40 dark:bg-teal-950/20 border-teal-200/80 dark:border-teal-900/60 hover:border-teal-400',
        'selesai' => 'bg-blue-50/40 dark:bg-blue-950/20 border-blue-200/80 dark:border-blue-900/60 hover:border-blue-400',
        'menunggu' => 'bg-amber-50/40 dark:bg-amber-950/20 border-amber-200/80 dark:border-amber-900/60',
        default => 'bg-white dark:bg-gray-800 border-slate-200/90 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-700',
    };
@endphp
<div x-data class="flex flex-col lg:flex-row justify-between items-start lg:items-center p-4 sm:p-5 rounded-2xl border transition hover:shadow-2xs cursor-pointer gap-4 focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900 {{ $cardAccent }}"
    role="button"
    tabindex="0"
    aria-label="Lihat detail lamaran {{ $app->position->judul_posisi }} di {{ $app->position->instansi->nama_dinas }}"
    x-on:click="$dispatch('open-modal', 'modal-lamaran-{{ $app->id }}')"
    x-on:keydown.enter.prevent="$dispatch('open-modal', 'modal-lamaran-{{ $app->id }}')"
    x-on:keydown.space.prevent="$dispatch('open-modal', 'modal-lamaran-{{ $app->id }}')">

    <div class="w-full lg:flex-1 min-w-0">
        <div class="flex flex-wrap items-center gap-2 mb-1.5">
            <h4 class="font-black text-slate-900 dark:text-gray-100 text-sm sm:text-base md:text-lg leading-tight">{{ $app->position->instansi->nama_dinas }}</h4>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase whitespace-nowrap border {{ $badges[$app->display_status] ?? 'bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-gray-300 border-slate-200 dark:border-gray-700' }}">
                {{ $app->display_status }}
            </span>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-600 flex items-center gap-1 whitespace-nowrap font-mono">
                <i class="fas fa-barcode text-[10px]"></i> {{ $app->nomor_registrasi ?? ('REG-' . $app->id) }}
            </span>
            @if($app->is_automatic_placement)
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/70 dark:border-teal-800/60 flex items-center gap-1 whitespace-nowrap">
                    <i class="fas fa-magic text-[10px]"></i> Penempatan Otomatis
                </span>
            @endif
        </div>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-gray-400 font-medium">{{ $app->position->judul_posisi }}</p>

        <div class="flex flex-col sm:flex-row sm:flex-wrap items-start sm:items-center gap-x-4 gap-y-1 mt-2 text-xs text-slate-500 dark:text-gray-400 font-medium">
            <span class="flex items-center gap-1.5">
                <i class="far fa-paper-plane text-slate-400 dark:text-gray-500"></i>
                Tgl Lamar: <span class="text-slate-800 dark:text-gray-200 font-bold">{{ \Carbon\Carbon::parse($app->created_at)->translatedFormat('d M Y') }}</span>
            </span>
            @if(in_array($appStatus, ['diterima', 'selesai']) && $app->tanggal_mulai)
            <span class="flex items-center gap-1.5">
                <i class="far fa-calendar-alt text-slate-400 dark:text-gray-500"></i>
                Periode: <span class="text-slate-800 dark:text-gray-200 font-bold">{{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y') }} &mdash; {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y') }}</span>
            </span>
            @endif
        </div>

        @if($app->nilai_angka)
            <div class="mt-2 inline-flex items-center px-3 py-1 bg-white dark:bg-gray-800 rounded-xl border border-blue-200 dark:border-blue-800/60 text-xs font-bold text-blue-800 dark:text-blue-300 shadow-2xs">
                <i class="fas fa-star mr-1 text-amber-400"></i> Nilai Akhir: {{ $app->nilai_angka }} ({{ $app->predikat }})
            </div>
        @endif
    </div>

    <div class="flex flex-wrap items-center gap-2 justify-start lg:justify-end w-full lg:w-auto shrink-0 mt-3 lg:mt-0" x-on:click.stop>
        @if(in_array($app->status?->value, ['pending', 'menunggu']) || ($app->status?->value === 'diterima' && $app->display_status === 'belum mulai'))
            <form action="{{ route('peserta.lamaran.batal', $app->id) }}" method="POST" @submit.prevent="$dispatch('open-confirm', { message: 'Apakah Anda yakin ingin membatalkan lamaran magang ini? Tindakan ini tidak dapat dikembalikan.', onConfirm: () => $el.submit() })">
                @csrf
                <button type="submit" class="min-h-[44px] w-full sm:w-auto justify-center px-3 sm:px-4 py-2.5 bg-rose-700 text-white rounded-xl text-xs font-bold hover:bg-rose-800 transition shadow-2xs flex items-center justify-center gap-2">
                    <i class="fas fa-times-circle"></i> Batalkan
                </button>
            </form>
        @endif

        <button type="button" class="min-h-[44px] justify-center px-3 sm:px-4 py-2.5 bg-teal-700 text-white rounded-xl text-xs font-bold hover:bg-teal-800 transition shadow-2xs flex items-center justify-center gap-2" x-on:click.prevent="$dispatch('open-modal', 'modal-lamaran-{{ $app->id }}')">
            <span><i class="fas fa-clipboard-list"></i> Detail</span>
        </button>
    </div>
</div>

@include('peserta.dashboard._lamaran-modal', ['app' => $app, 'badges' => $badges])
