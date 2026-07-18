@props([
    'title' => 'Belum Ada Data',
    'description' => 'Data yang Anda cari tidak ditemukan atau belum ditambahkan.',
    'icon' => 'fa-folder-open',
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center p-8 md:p-12 bg-white rounded-3xl border border-gray-100 shadow-sm text-center max-w-xl mx-auto']) }}>
    <div class="w-16 h-16 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 mb-6 ring-8 ring-teal-50/50">
        <i class="fas {{ $icon }} text-2xl animate-pulse"></i>
    </div>
    <h3 class="text-lg font-black text-gray-900 mb-2">
        {{ $title }}
    </h3>
    <p class="text-sm text-gray-500 font-medium leading-relaxed mb-6 max-w-sm">
        {{ $description }}
    </p>
    @if($slot->isNotEmpty())
        <div class="flex flex-wrap items-center justify-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>
