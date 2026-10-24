<x-guest-layout :public-navigation="true">
    <div class="verification-shell" x-data="{ copied: false }">
        <div class="mx-auto w-full max-w-5xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8 lg:py-16">
            <nav class="mb-8 flex flex-wrap items-center justify-between gap-4" aria-label="Navigasi verifikasi">
                <a href="{{ route('home') }}" class="verification-back">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    <span>Kembali ke beranda</span>
                </a>
                <a href="{{ route('qr.scanner') }}" class="verification-back">
                    <i class="fas fa-qrcode text-teal-700 dark:text-teal-400" aria-hidden="true"></i>
                    <span>Verifikasi dokumen lain</span>
                </a>
            </nav>

            <div class="mb-8 flex items-center gap-4">
                <span class="verification-kicker">Verifikasi digital</span>
                <span class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></span>
                <span class="hidden text-xs font-medium text-slate-500 dark:text-slate-400 sm:block">Pemerintah Kota Banjarmasin</span>
            </div>

            <main class="verification-card overflow-hidden">
                @if(!$isValid || $idCardStatus === 'invalid')
                    <header class="verification-status verification-status-danger">
                        <div class="verification-status-icon bg-rose-600 text-white">
                            <i class="fas fa-id-badge" aria-hidden="true"></i>
                        </div>
                        <div>
                            <p class="verification-status-label text-rose-700 dark:text-rose-300">Verifikasi gagal</p>
                            <h1 class="verification-title">ID Card tidak ditemukan</h1>
                            <p class="verification-description">Token verifikasi tidak terdaftar pada pangkalan data resmi Pemerintah Kota Banjarmasin.</p>
                        </div>
                    </header>

                    <div class="verification-content mx-auto max-w-2xl text-center">
                        @if(!empty($searchedToken))
                            <div class="verification-token mb-6 text-left">
                                <span class="verification-data-label mb-2 block">Token yang dipindai</span>
                                <code class="break-all text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $searchedToken }}</code>
                            </div>
                        @endif
                        <div class="mb-7 flex items-start gap-3 text-left sm:text-center">
                            <i class="fas fa-circle-info mt-0.5 shrink-0 text-slate-400" aria-hidden="true"></i>
                            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">Pastikan Anda memindai QR Code resmi pada ID Card fisik atau elektronik peserta magang. Periksa kembali kode dan pastikan seluruh area QR terlihat jelas.</p>
                        </div>
                        <div class="flex flex-col justify-center gap-3 sm:flex-row">
                            <a href="{{ route('qr.scanner') }}" class="verification-button"><i class="fas fa-camera" aria-hidden="true"></i>Coba pindai ulang</a>
                            <a href="{{ route('home') }}" class="verification-button-secondary">Kembali ke beranda</a>
                        </div>
                    </div>

                @elseif($idCardStatus === 'revoked')
                    <header class="verification-status verification-status-danger">
                        <div class="verification-status-icon bg-rose-600 text-white">
                            <i class="fas fa-ban" aria-hidden="true"></i>
                        </div>
                        <div>
                            <p class="verification-status-label text-rose-700 dark:text-rose-300">Kartu tidak berlaku</p>
                            <h1 class="verification-title">ID Card telah dinonaktifkan</h1>
                            <p class="verification-description">Status kepesertaan <strong>{{ strtoupper($app->status_value) }}</strong>. Kartu ini tidak lagi dapat digunakan sebagai tanda pengenal resmi.</p>
                        </div>
                    </header>

                    <div class="verification-content space-y-5">
                        <section class="verification-profile">
                            <div class="verification-photo">
                                @if($app->user->photo && Storage::disk('public')->exists($app->user->photo))
                                    <img src="{{ Storage::disk('public')->url($app->user->photo) }}" class="h-full w-full object-cover grayscale" alt="Foto {{ $app->user->name }}">
                                @else
                                    <i class="fas fa-user text-2xl" aria-hidden="true"></i>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="verification-data-label mb-1">Identitas pemilik kartu</p>
                                <h2 class="verification-profile-name">{{ $app->user->name }}</h2>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $app->user->asal_instansi ?? 'Institusi Pendidikan' }}</p>
                                <span class="mt-3 inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-bold text-rose-700 dark:bg-rose-950 dark:text-rose-300">Status: {{ ucfirst($app->status_value) }}</span>
                            </div>
                        </section>
                        <div class="verification-alert border-rose-200 bg-rose-50 text-rose-800 dark:border-rose-900 dark:bg-rose-950/30 dark:text-rose-200">
                            <i class="fas fa-triangle-exclamation mt-0.5 shrink-0" aria-hidden="true"></i>
                            <p>Pemilik kartu tidak memiliki hak akses kerja maupun kegiatan kedinasan di lingkungan Pemerintah Kota Banjarmasin.</p>
                        </div>
                    </div>

                @elseif($idCardStatus === 'finished')
                    <header class="verification-status verification-status-info">
                        <div class="verification-status-icon bg-sky-600 text-white">
                            <i class="fas fa-user-graduate" aria-hidden="true"></i>
                        </div>
                        <div>
                            <p class="verification-status-label text-sky-700 dark:text-sky-300">Masa magang selesai</p>
                            <h1 class="verification-title">Periode magang telah berakhir</h1>
                            <p class="verification-description">Peserta telah menyelesaikan program magang. Kartu ini tersimpan sebagai arsip tanda pengenal.</p>
                        </div>
                    </header>

                    <div class="verification-content space-y-6">
                        <section class="verification-profile">
                            <div class="verification-photo border-sky-200 dark:border-sky-800">
                                @if($app->user->photo && Storage::disk('public')->exists($app->user->photo))
                                    <img src="{{ Storage::disk('public')->url($app->user->photo) }}" class="h-full w-full object-cover" alt="Foto {{ $app->user->name }}">
                                @else
                                    <i class="fas fa-user text-2xl" aria-hidden="true"></i>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="verification-data-label mb-1">Alumni peserta magang</p>
                                <h2 class="verification-profile-name">{{ $app->user->name }}</h2>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $app->user->asal_instansi ?? '-' }}</p>
                                @if(!empty($app->user->nik))
                                    <p class="mt-3 font-mono text-xs font-semibold text-slate-600 dark:text-slate-300">NIK / NIM &nbsp; {{ $app->user->masked_nik }}</p>
                                @endif
                            </div>
                        </section>

                        <div class="grid gap-px overflow-hidden rounded-2xl border border-slate-200 bg-slate-200 dark:border-slate-700 dark:bg-slate-700 sm:grid-cols-2">
                            <div class="verification-detail">
                                <span class="verification-data-label">Instansi penempatan</span>
                                <strong class="verification-data-value">{{ $app->position->instansi->nama_dinas }}</strong>
                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $app->position->judul_posisi }}</span>
                            </div>
                            <div class="verification-detail">
                                <span class="verification-data-label">Periode magang</span>
                                <strong class="verification-data-value">{{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d M Y') }}</strong>
                            </div>
                        </div>

                        @if($app->nomor_sertifikat || ($app->certificate && $app->certificate->isActive()))
                            <div class="flex flex-col gap-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900 dark:bg-emerald-950/30 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-award mt-1 text-emerald-600 dark:text-emerald-400" aria-hidden="true"></i>
                                    <div>
                                        <strong class="block text-sm text-emerald-900 dark:text-emerald-200">Sertifikat kelulusan tersedia</strong>
                                        <p class="mt-1 break-all font-mono text-xs text-emerald-700 dark:text-emerald-300">{{ $app->nomor_sertifikat ?? $app->certificate?->nomor_sertifikat }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('certificate.verify', $app->token_verifikasi) }}" class="inline-flex min-h-11 shrink-0 items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 text-xs font-bold text-white transition hover:bg-emerald-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">Lihat sertifikat<i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        @endif
                        <div class="flex justify-end border-t border-slate-100 pt-5 dark:border-slate-800">
                            <a href="{{ route('qr.scanner') }}" class="verification-button-secondary"><i class="fas fa-qrcode" aria-hidden="true"></i>Pindai kartu lain</a>
                        </div>
                    </div>

                @else
                    <header class="verification-status verification-status-success">
                        <div class="verification-status-icon bg-teal-700 text-white">
                            <i class="fas fa-id-card" aria-hidden="true"></i>
                        </div>
                        <div>
                            <div class="mb-2 flex flex-wrap items-center gap-2">
                                <p class="verification-status-label text-teal-800 dark:text-teal-300">ID Card resmi</p>
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-teal-300 bg-white/60 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-teal-800 dark:border-teal-700 dark:bg-teal-950/40 dark:text-teal-300"><span class="h-1.5 w-1.5 rounded-full bg-teal-600 dark:bg-teal-400"></span>Aktif</span>
                            </div>
                            <h1 class="verification-title">Peserta magang terverifikasi</h1>
                            <p class="verification-description">Identitas dan status kepesertaan cocok dengan data resmi Pemerintah Kota Banjarmasin.</p>
                        </div>
                    </header>

                    <div class="verification-content space-y-6">
                        <section class="verification-profile">
                            <div class="verification-photo h-24 w-24 border-teal-200 dark:border-teal-800 sm:h-28 sm:w-28">
                                @if($app->user->photo && Storage::disk('public')->exists($app->user->photo))
                                    <img src="{{ Storage::disk('public')->url($app->user->photo) }}" class="h-full w-full object-cover" alt="Foto {{ $app->user->name }}">
                                @else
                                    <div class="flex h-full w-full flex-col items-center justify-center gap-2"><i class="fas fa-user text-2xl" aria-hidden="true"></i><span class="text-[9px] font-bold uppercase tracking-wider">Peserta</span></div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="verification-data-label mb-1">Identitas peserta</p>
                                <h2 class="verification-profile-name text-2xl sm:text-3xl">{{ $app->user->name }}</h2>
                                <p class="mt-1.5 text-sm font-medium text-slate-600 dark:text-slate-300">{{ $app->user->asal_instansi ?? 'Institusi Pendidikan' }}</p>
                                <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-xs text-slate-500 dark:text-slate-400">
                                    @if(!empty($app->user->nik))
                                        <span class="font-mono font-semibold">NIK / NIM &nbsp; {{ $app->user->masked_nik }}</span>
                                    @endif
                                    @if(!empty($app->user->majorDetail?->name) || !empty($app->user->major))
                                        <span class="inline-flex items-center gap-1.5"><i class="fas fa-graduation-cap text-teal-700 dark:text-teal-400" aria-hidden="true"></i>{{ $app->user->majorDetail?->name ?? $app->user->major }}</span>
                                    @endif
                                </div>
                            </div>
                        </section>

                        <section aria-labelledby="id-details-heading">
                            <div class="mb-3 flex items-center justify-between">
                                <h2 id="id-details-heading" class="text-sm font-bold text-slate-900 dark:text-white">Informasi penempatan</h2>
                                <span class="text-xs text-slate-400">Detail kepesertaan</span>
                            </div>
                            <dl class="grid gap-px overflow-hidden rounded-2xl border border-slate-200 bg-slate-200 dark:border-slate-700 dark:bg-slate-700 sm:grid-cols-2">
                                <div class="verification-detail">
                                    <dt class="verification-data-label"><i class="fas fa-building mr-1.5 text-teal-700 dark:text-teal-400" aria-hidden="true"></i>Instansi penempatan</dt>
                                    <dd class="verification-data-value">{{ $app->position->instansi->nama_dinas }}</dd>
                                    @if(!empty($app->position->instansi->alamat))<dd class="text-xs leading-5 text-slate-500 dark:text-slate-400">{{ $app->position->instansi->alamat }}</dd>@endif
                                </div>
                                <div class="verification-detail">
                                    <dt class="verification-data-label"><i class="fas fa-briefcase mr-1.5 text-teal-700 dark:text-teal-400" aria-hidden="true"></i>Posisi / divisi</dt>
                                    <dd class="verification-data-value">{{ $app->position->judul_posisi }}</dd>
                                    <dd class="text-xs text-slate-500 dark:text-slate-400">Kode unit: {{ $app->position->instansi->kode_unit_kerja ?? '-' }}</dd>
                                </div>
                                <div class="verification-detail">
                                    <dt class="verification-data-label"><i class="far fa-calendar mr-1.5 text-teal-700 dark:text-teal-400" aria-hidden="true"></i>Periode pelaksanaan</dt>
                                    <dd class="verification-data-value">{{ \Carbon\Carbon::parse($app->tanggal_mulai)->translatedFormat('d F Y') }}</dd>
                                    <dd class="text-xs text-slate-500 dark:text-slate-400">hingga {{ \Carbon\Carbon::parse($app->tanggal_selesai)->translatedFormat('d F Y') }}</dd>
                                </div>
                                <div class="verification-detail">
                                    <dt class="verification-data-label"><i class="far fa-user mr-1.5 text-teal-700 dark:text-teal-400" aria-hidden="true"></i>Pembimbing lapangan</dt>
                                    <dd class="verification-data-value">{{ $app->pembimbing_lapangan->name ?? 'Ditetapkan Dinas' }}</dd>
                                    @if(!empty($app->pembimbing_lapangan->nip))<dd class="font-mono text-xs text-slate-500 dark:text-slate-400">NIP: {{ $app->pembimbing_lapangan->nip }}</dd>@endif
                                </div>
                            </dl>
                        </section>

                        <div class="verification-alert border-teal-200 bg-teal-50 text-teal-900 dark:border-teal-900 dark:bg-teal-950/30 dark:text-teal-100">
                            <i class="fas fa-shield-halved mt-0.5 shrink-0 text-teal-700 dark:text-teal-400" aria-hidden="true"></i>
                            <p><strong class="font-bold">Tanda pengenal resmi.</strong> Kartu ini sah digunakan selama periode magang di kantor atau unit kerja yang tertera.</p>
                        </div>

                        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
                            <button type="button" @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2500)" class="verification-button-secondary" :class="copied ? '!border-emerald-300 !text-emerald-700 dark:!text-emerald-300' : ''">
                                <i class="fas" :class="copied ? 'fa-check' : 'fa-link'" aria-hidden="true"></i>
                                <span x-text="copied ? 'Tautan berhasil disalin' : 'Salin tautan verifikasi'">Salin tautan verifikasi</span>
                            </button>
                            <a href="{{ route('qr.scanner') }}" class="verification-button"><i class="fas fa-qrcode" aria-hidden="true"></i>Pindai kartu lain</a>
                        </div>
                    </div>
                @endif

                <footer class="verification-footer">
                    <i class="fas fa-lock text-teal-700 dark:text-teal-400" aria-hidden="true"></i>
                    <span>Hasil verifikasi diterbitkan otomatis oleh SiMagang · Pemerintah Kota Banjarmasin</span>
                </footer>
            </main>

            <p class="mt-6 text-center text-xs leading-6 text-slate-500 dark:text-slate-400">Data ditampilkan sesuai catatan terakhir pada sistem. Jangan bagikan data pribadi kepada pihak yang tidak berkepentingan.</p>
        </div>
    </div>
</x-guest-layout>
