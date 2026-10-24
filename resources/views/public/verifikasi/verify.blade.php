<x-guest-layout :public-navigation="true">
    <div class="verification-shell" x-data="{ copied: false }">
        <div class="mx-auto w-full max-w-5xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8 lg:py-16">
            <nav class="mb-8 flex flex-wrap items-center justify-between gap-4" aria-label="Navigasi verifikasi">
                <a href="{{ route('home') }}" class="verification-back"><i class="fas fa-arrow-left" aria-hidden="true"></i>Kembali ke beranda</a>
                <a href="{{ route('qr.scanner') }}" class="verification-back"><i class="fas fa-qrcode text-teal-700 dark:text-teal-400" aria-hidden="true"></i>Verifikasi dokumen lain</a>
            </nav>

            <div class="mb-8 flex items-center gap-4">
                <span class="verification-kicker">Verifikasi digital</span>
                <span class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></span>
                <span class="hidden text-xs font-medium text-slate-500 dark:text-slate-400 sm:block">Pemerintah Kota Banjarmasin</span>
            </div>

            <main class="verification-card overflow-hidden">
                @if(!$isValid)
                    <header class="verification-status verification-status-danger">
                        <div class="verification-status-icon bg-rose-600 text-white"><i class="fas fa-file-circle-xmark" aria-hidden="true"></i></div>
                        <div>
                            <p class="verification-status-label text-rose-700 dark:text-rose-300">Verifikasi gagal</p>
                            <h1 class="verification-title">Dokumen tidak ditemukan</h1>
                            <p class="verification-description">Nomor sertifikat atau token verifikasi tidak terdaftar pada pangkalan data resmi Pemerintah Kota Banjarmasin.</p>
                        </div>
                    </header>
                    <div class="verification-content mx-auto max-w-2xl text-center">
                        @if(!empty($searchedToken))
                            <div class="verification-token mb-6 text-left">
                                <span class="verification-data-label mb-2 block">Kata kunci / token yang dicari</span>
                                <code class="break-all text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $searchedToken }}</code>
                            </div>
                        @endif
                        <div class="mb-7 flex items-start gap-3 text-left sm:text-center">
                            <i class="fas fa-circle-info mt-0.5 shrink-0 text-slate-400" aria-hidden="true"></i>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">Pastikan QR Code berasal dari sertifikat fisik atau elektronik resmi. Anda juga dapat memeriksa ulang ejaan nomor sertifikat melalui pencarian manual.</p>
                        </div>
                        <div class="flex flex-col justify-center gap-3 sm:flex-row">
                            <a href="{{ route('qr.scanner') }}" class="verification-button"><i class="fas fa-camera" aria-hidden="true"></i>Coba verifikasi lagi</a>
                            <a href="{{ route('home') }}" class="verification-button-secondary">Kembali ke beranda</a>
                        </div>
                    </div>

                @elseif($app->certificate && $app->certificate->isRevoked())
                    <header class="verification-status verification-status-danger">
                        <div class="verification-status-icon bg-rose-600 text-white"><i class="fas fa-ban" aria-hidden="true"></i></div>
                        <div>
                            <p class="verification-status-label text-rose-700 dark:text-rose-300">Dokumen tidak berlaku</p>
                            <h1 class="verification-title">Sertifikat telah dibatalkan</h1>
                            <p class="verification-description">Keabsahan sertifikat ini telah dicabut secara resmi oleh Pemerintah Kota Banjarmasin.</p>
                        </div>
                    </header>
                    <div class="verification-content space-y-5">
                        <section class="rounded-2xl border border-rose-200 bg-rose-50 p-5 dark:border-rose-900 dark:bg-rose-950/30 sm:p-6" aria-labelledby="revocation-heading">
                            <h2 id="revocation-heading" class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-rose-800 dark:text-rose-300"><i class="fas fa-triangle-exclamation" aria-hidden="true"></i>Alasan pembatalan resmi</h2>
                            <p class="mt-3 text-sm font-medium leading-7 text-rose-950 dark:text-rose-100">{{ $app->certificate->revoked_reason ?? 'Pencabutan status legalitas dan pembatalan hak sertifikat oleh administrator.' }}</p>
                            @if($app->certificate->revoked_at)
                                <p class="mt-4 border-t border-rose-200 pt-3 text-xs text-rose-700 dark:border-rose-900 dark:text-rose-300">Dibatalkan pada {{ $app->certificate->revoked_at->translatedFormat('d F Y, H:i') }} WITA</p>
                            @endif
                        </section>
                        <section class="verification-profile">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800"><i class="fas fa-user text-xl" aria-hidden="true"></i></div>
                            <div class="min-w-0 flex-1">
                                <p class="verification-data-label mb-1">Identitas pada dokumen</p>
                                <h2 class="verification-profile-name">{{ $app->user->name }}</h2>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $app->user->asal_instansi ?? 'Institusi Pendidikan' }}</p>
                                <p class="mt-3 break-all font-mono text-xs font-semibold text-slate-500 dark:text-slate-400">No. sertifikat &nbsp; <span class="line-through">{{ $app->nomor_sertifikat ?? '-' }}</span></p>
                            </div>
                        </section>
                        <div class="verification-alert border-amber-200 bg-amber-50 text-amber-900 dark:border-amber-900 dark:bg-amber-950/30 dark:text-amber-100">
                            <i class="fas fa-circle-exclamation mt-0.5 shrink-0" aria-hidden="true"></i>
                            <p>Dokumen ini tidak dapat digunakan untuk keperluan akademik, kedinasan, maupun administrasi lainnya.</p>
                        </div>
                    </div>

                @elseif($app->status?->value === 'selesai')
                    <header class="verification-status verification-status-success">
                        <div class="verification-status-icon bg-teal-700 text-white"><i class="fas fa-award" aria-hidden="true"></i></div>
                        <div>
                            <div class="mb-2 flex flex-wrap items-center gap-2">
                                <p class="verification-status-label text-teal-800 dark:text-teal-300">Sertifikat resmi</p>
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-teal-300 bg-white/60 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-teal-800 dark:border-teal-700 dark:bg-teal-950/40 dark:text-teal-300"><i class="fas fa-check" aria-hidden="true"></i>Sah & valid</span>
                            </div>
                            <h1 class="verification-title">Sertifikat berhasil diverifikasi</h1>
                            <p class="verification-description">Dokumen terdaftar dan cocok dengan catatan resmi Program Magang Pemerintah Kota Banjarmasin.</p>
                        </div>
                    </header>

                    <div class="verification-content space-y-6">
                        <section class="border-b border-slate-100 pb-6 text-center dark:border-slate-800">
                            <p class="verification-data-label mb-2">Diberikan kepada</p>
                            <h2 class="font-display text-2xl font-bold tracking-tight text-slate-950 dark:text-white sm:text-3xl">{{ $app->user->name }}</h2>
                            <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">{{ $app->user->asal_instansi ?? 'Institusi Pendidikan' }}</p>
                            @if(!empty($app->user->nik))<p class="mt-3 font-mono text-xs font-semibold text-slate-500 dark:text-slate-400">NIK / NIM &nbsp; {{ $app->user->masked_nik }}</p>@endif
                        </section>

                        <section aria-labelledby="certificate-details-heading">
                            <div class="mb-3 flex items-center justify-between">
                                <h2 id="certificate-details-heading" class="text-sm font-bold text-slate-900 dark:text-white">Rincian sertifikat</h2>
                                <span class="text-xs text-slate-400">Data kelulusan</span>
                            </div>
                            <dl class="grid gap-px overflow-hidden rounded-2xl border border-slate-200 bg-slate-200 dark:border-slate-700 dark:bg-slate-700 sm:grid-cols-2">
                                <div class="verification-detail">
                                    <dt class="verification-data-label"><i class="fas fa-hashtag mr-1.5 text-teal-700 dark:text-teal-400" aria-hidden="true"></i>Nomor registrasi</dt>
                                    <dd class="verification-data-value break-all font-mono">{{ $app->nomor_sertifikat ?? '-' }}</dd>
                                </div>
                                <div class="verification-detail">
                                    <dt class="verification-data-label"><i class="fas fa-star mr-1.5 text-teal-700 dark:text-teal-400" aria-hidden="true"></i>Predikat & nilai akhir</dt>
                                    <dd class="flex flex-wrap items-center gap-2"><strong class="verification-data-value">{{ $app->predikat ?: \App\Models\Application::predikatFor((float) $app->avg_nilai) }}</strong><span class="rounded-md bg-emerald-100 px-2 py-0.5 font-mono text-xs font-bold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">{{ number_format((float) $app->avg_nilai, 1) }}</span></dd>
                                </div>
                                <div class="verification-detail">
                                    <dt class="verification-data-label"><i class="fas fa-building mr-1.5 text-teal-700 dark:text-teal-400" aria-hidden="true"></i>Instansi penempatan</dt>
                                    <dd class="verification-data-value">{{ $app->position->instansi->nama_dinas }}</dd>
                                    <dd class="text-xs text-slate-500 dark:text-slate-400">{{ $app->position->judul_posisi }}</dd>
                                </div>
                                <div class="verification-detail">
                                    <dt class="verification-data-label"><i class="far fa-calendar mr-1.5 text-teal-700 dark:text-teal-400" aria-hidden="true"></i>Periode magang</dt>
                                    <dd class="verification-data-value">{{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y') }}</dd>
                                    <dd class="text-xs text-slate-500 dark:text-slate-400">hingga {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y') }}</dd>
                                </div>
                            </dl>
                        </section>

                        @if($app->certificate)
                            <section class="flex flex-col gap-4 rounded-2xl border border-teal-200 bg-teal-50/70 p-5 dark:border-teal-900 dark:bg-teal-950/30 sm:flex-row sm:items-center" aria-labelledby="signature-heading">
                                @if($app->certificate->qr_code_path)
                                    <div class="mx-auto h-20 w-20 shrink-0 rounded-xl border border-teal-200 bg-white p-2 dark:border-teal-800 sm:mx-0"><img src="{{ Storage::url($app->certificate->qr_code_path) }}" alt="QR Code verifikasi sertifikat" class="h-full w-full object-contain"></div>
                                @endif
                                <div class="min-w-0 flex-1 text-center sm:text-left">
                                    <h2 id="signature-heading" class="verification-data-label text-teal-800 dark:text-teal-300">Penandatangan resmi</h2>
                                    <p class="mt-1 text-sm font-bold text-slate-900 dark:text-white">{{ $app->certificate->signer_name ?? 'Pemerintah Kota Banjarmasin' }}</p>
                                    <p class="mt-2 break-all font-mono text-[10px] leading-5 text-slate-500 dark:text-slate-400">Signature hash: <span class="select-all text-teal-800 dark:text-teal-300">{{ substr($app->certificate->signature_mock ?? $app->token_verifikasi, 0, 32) }}...</span></p>
                                </div>
                                <i class="fas fa-shield-halved hidden text-2xl text-teal-700/40 dark:text-teal-400/40 sm:block" aria-hidden="true"></i>
                            </section>
                        @endif

                        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
                            <button type="button" @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2500)" class="verification-button-secondary" :class="copied ? '!border-emerald-300 !text-emerald-700 dark:!text-emerald-300' : ''">
                                <i class="fas" :class="copied ? 'fa-check' : 'fa-link'" aria-hidden="true"></i><span x-text="copied ? 'Tautan berhasil disalin' : 'Salin tautan verifikasi'">Salin tautan verifikasi</span>
                            </button>
                            <a href="{{ route('qr.scanner') }}" class="verification-button"><i class="fas fa-qrcode" aria-hidden="true"></i>Periksa dokumen lain</a>
                        </div>
                    </div>

                @else
                    <header class="verification-status verification-status-info">
                        <div class="verification-status-icon bg-sky-600 text-white"><i class="fas fa-user-clock" aria-hidden="true"></i></div>
                        <div>
                            <p class="verification-status-label text-sky-700 dark:text-sky-300">Peserta aktif</p>
                            <h1 class="verification-title">Peserta sedang menjalani magang</h1>
                            <p class="verification-description">Peserta terdaftar resmi dalam periode aktif. Sertifikat belum diterbitkan.</p>
                        </div>
                    </header>
                    <div class="verification-content space-y-6">
                        <section class="border-b border-slate-100 pb-6 text-center dark:border-slate-800">
                            <p class="verification-data-label mb-2">Identitas peserta</p>
                            <h2 class="font-display text-2xl font-bold tracking-tight text-slate-950 dark:text-white sm:text-3xl">{{ $app->user->name }}</h2>
                            <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">{{ $app->user->asal_instansi ?? 'Institusi Pendidikan' }}</p>
                        </section>
                        <dl class="grid gap-px overflow-hidden rounded-2xl border border-slate-200 bg-slate-200 dark:border-slate-700 dark:bg-slate-700 sm:grid-cols-2">
                            <div class="verification-detail">
                                <dt class="verification-data-label"><i class="fas fa-building mr-1.5 text-sky-700 dark:text-sky-400" aria-hidden="true"></i>Instansi penempatan</dt>
                                <dd class="verification-data-value">{{ $app->position->instansi->nama_dinas }}</dd>
                                <dd class="text-xs text-slate-500 dark:text-slate-400">{{ $app->position->judul_posisi }}</dd>
                            </div>
                            <div class="verification-detail">
                                <dt class="verification-data-label"><i class="far fa-calendar mr-1.5 text-sky-700 dark:text-sky-400" aria-hidden="true"></i>Periode magang</dt>
                                <dd class="verification-data-value">{{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y') }}</dd>
                                <dd class="text-xs text-slate-500 dark:text-slate-400">hingga {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y') }}</dd>
                            </div>
                        </dl>
                        <div class="verification-alert border-sky-200 bg-sky-50 text-sky-900 dark:border-sky-900 dark:bg-sky-950/30 dark:text-sky-100">
                            <i class="fas fa-circle-info mt-0.5 shrink-0" aria-hidden="true"></i>
                            <p>Sertifikat elektronik diterbitkan setelah peserta menyelesaikan periode magang dan memperoleh penilaian dari pembimbing lapangan.</p>
                        </div>
                        <div class="flex justify-end border-t border-slate-100 pt-5 dark:border-slate-800"><a href="{{ route('qr.scanner') }}" class="verification-button-secondary"><i class="fas fa-qrcode" aria-hidden="true"></i>Periksa dokumen lain</a></div>
                    </div>
                @endif

                <footer class="verification-footer"><i class="fas fa-lock text-teal-700 dark:text-teal-400" aria-hidden="true"></i><span>Hasil verifikasi diterbitkan otomatis oleh SiMagang · Pemerintah Kota Banjarmasin</span></footer>
            </main>

            <p class="mt-6 text-center text-xs leading-6 text-slate-500 dark:text-slate-400">Data ditampilkan sesuai catatan terakhir pada sistem. Jangan bagikan data pribadi kepada pihak yang tidak berkepentingan.</p>
        </div>
    </div>
</x-guest-layout>
