<section>
    <div class="border-b border-gray-100 pb-5 mb-6">
        <h3 class="font-semibold text-lg text-gray-900 flex items-center gap-2">
            <i class="fas fa-user-circle text-teal-600"></i>
            {{ __('Profile Information') }}
        </h3>
        <p class="text-sm text-gray-500 mt-1">
            {{ __("Perbarui foto profil, informasi dasar akun, dan data akademik atau instansi Anda.") }}
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" enctype="multipart/form-data" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Foto Profil dengan Preview Langsung -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 p-5 rounded-xl bg-gray-50/80 border border-gray-200/60 mb-6">
            <div class="relative flex shrink-0 overflow-hidden rounded-full w-20 h-20 border-2 border-white shadow-md ring-2 ring-teal-500/20">
                @if ($user->photo && \Illuminate\Support\Facades\Storage::exists('public/' . $user->photo))
                    <img id="profile-preview" src="{{ asset('storage/' . $user->photo) }}" alt="Foto Profil" class="h-full w-full object-cover">
                @else
                    <img id="profile-preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=0D9488&background=CCFBF1&size=128" alt="Foto Profil Default" class="h-full w-full object-cover">
                @endif
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-3">
                    <label for="photo" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium transition-all border border-gray-300 bg-white shadow-2xs hover:bg-gray-50 hover:text-gray-900 text-gray-700 h-9 px-4 py-2 cursor-pointer active:scale-95">
                        <i class="fas fa-camera text-teal-600 text-xs"></i>
                        <span>Change Photo</span>
                        <input id="photo" name="photo" type="file" onchange="previewPhoto(event)" class="sr-only" accept="image/*" />
                    </label>
                    @if ($user->photo)
                        <span class="text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-200/60 px-2.5 py-1 rounded-md">
                            <i class="fas fa-check-circle mr-1"></i> Foto Terupload
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maksimal ukuran 2MB. Disarankan pas foto formal atau rapi.</p>
                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Nama -->
            <div class="space-y-1.5">
                <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 select-none" for="name">
                    <span>{{ __('Full Name') }}</span> <span class="text-red-500">*</span>
                </label>
                <input id="name" name="name" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-2xs transition-all outline-none focus:border-teal-600 focus:ring-3 focus:ring-teal-600/15" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                <x-input-error class="mt-1" :messages="$errors->get('name')" />
            </div>

            <!-- Username -->
            <div class="space-y-1.5">
                <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 select-none" for="username">
                    <span>{{ __('Username') }}</span> <span class="text-red-500">*</span>
                </label>
                <input id="username" name="username" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-2xs transition-all outline-none focus:border-teal-600 focus:ring-3 focus:ring-teal-600/15" value="{{ old('username', $user->username) }}" required autocomplete="username" placeholder="username_anda" />
                <p class="text-[11px] text-gray-400">Digunakan untuk login alternatif selain email.</p>
                <x-input-error class="mt-1" :messages="$errors->get('username')" />
            </div>

            <!-- Email -->
            <div class="space-y-1.5 md:col-span-2">
                <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 select-none" for="email">
                    <span>{{ __('Email Address') }}</span> <span class="text-red-500">*</span>
                </label>
                <input id="email" name="email" type="email" class="w-full min-w-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-2xs transition-all outline-none focus:border-teal-600 focus:ring-3 focus:ring-teal-600/15" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                <x-input-error class="mt-1" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 flex items-center justify-between">
                        <span>{{ __('Alamat email Anda belum diverifikasi.') }}</span>
                        <button form="send-verification" class="font-bold underline text-amber-900 hover:text-amber-700">
                            {{ __('Kirim ulang verifikasi.') }}
                        </button>
                    </div>
                @endif
            </div>
        </div>

        @if ($user->role === 'pembimbing_lapangan' || $user->role === 'dinas')
        <div class="border-t border-gray-100 pt-5 mt-5">
            <h4 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                <i class="fas fa-file-signature text-teal-600"></i>
                <span>{{ __('Tanda Tangan & Paraf Digital') }}</span>
            </h4>
            <div class="p-4 rounded-xl bg-gray-50/80 border border-gray-200/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex-1 space-y-1">
                    <label for="signature" class="text-sm font-medium text-gray-700">Upload Tanda Tangan (PNG/JPG Transparan)</label>
                    <input id="signature" name="signature" type="file" class="block w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" accept="image/*" />
                    <x-input-error class="mt-1" :messages="$errors->get('signature')" />
                </div>
                @if ($user->signature)
                <div class="shrink-0 text-center bg-white p-2 rounded-lg border border-gray-200 shadow-2xs">
                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Saat Ini:</p>
                    <img src="{{ asset('storage/' . $user->signature) }}" alt="Signature" class="h-14 mx-auto object-contain">
                </div>
                @endif
            </div>
        </div>
        @endif

        @if ($user->role === 'pembimbing')
        <div class="border-t border-gray-100 pt-5 mt-5">
            <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-briefcase text-teal-600"></i>
                <span>{{ __('Informasi Dosen / Pembimbing') }}</span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- NIDN / NIP -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 select-none" for="nik">
                        <span>{{ __('NIDN / NIP / NIK') }}</span>
                    </label>
                    <input id="nik" name="nik" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-2xs transition-all outline-none focus:border-teal-600 focus:ring-3 focus:ring-teal-600/15" value="{{ old('nik', $user->nik) }}" placeholder="Nomor Induk Dosen / Pegawai" />
                    <x-input-error class="mt-1" :messages="$errors->get('nik')" />
                </div>

                <!-- No HP -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 select-none" for="phone">
                        <span>{{ __('Nomor WhatsApp') }}</span>
                    </label>
                    <input id="phone" name="phone" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-2xs transition-all outline-none focus:border-teal-600 focus:ring-3 focus:ring-teal-600/15" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 0812xxxx" />
                    <x-input-error class="mt-1" :messages="$errors->get('phone')" />
                </div>

                <!-- Asal Instansi -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 select-none" for="asal_instansi">
                        <span>{{ __('Asal Sekolah / Universitas') }}</span> <span class="text-red-500">*</span>
                    </label>
                    <input id="asal_instansi" name="asal_instansi" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-2xs transition-all outline-none focus:border-teal-600 focus:ring-3 focus:ring-teal-600/15" value="{{ old('asal_instansi', $user->asal_instansi) }}" placeholder="Contoh: Universitas Lambung Mangkurat" required />
                    <p class="text-xs text-teal-600 mt-1 font-bold"><i class="fas fa-info-circle mr-1"></i> Penting! Ketikkan nama kampus persis dengan yang diketik mahasiswa agar mereka bisa menemukan nama Anda di pilihan pembimbing.</p>
                    <x-input-error class="mt-1" :messages="$errors->get('asal_instansi')" />
                </div>
            </div>
        </div>
        @endif

        @if ($user->role === 'peserta')
        <div class="border-t border-gray-100 pt-5 mt-5">
            <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-user-graduate text-teal-600"></i>
                <span>{{ __('Informasi Akademik & Magang') }}</span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- NIM / NPM -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 select-none" for="nik">
                        <span>{{ __('NIM / NPM') }}</span>
                    </label>
                    <input id="nik" name="nik" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-2xs transition-all outline-none focus:border-teal-600 focus:ring-3 focus:ring-teal-600/15" value="{{ old('nik', $user->nik) }}" placeholder="Nomor Induk Mahasiswa" />
                    <p class="text-[11px] text-gray-400">Nomor ini akan tercetak di sertifikat magang.</p>
                    <x-input-error class="mt-1" :messages="$errors->get('nik')" />
                </div>

                <!-- No HP -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 select-none" for="phone">
                        <span>{{ __('Nomor WhatsApp') }}</span>
                    </label>
                    <input id="phone" name="phone" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-2xs transition-all outline-none focus:border-teal-600 focus:ring-3 focus:ring-teal-600/15" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 0812xxxx" />
                    <x-input-error class="mt-1" :messages="$errors->get('phone')" />
                </div>

                <!-- Asal Instansi -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 select-none" for="asal_instansi">
                        <span>{{ __('Asal Sekolah / Universitas') }}</span> <span class="text-red-500">*</span>
                    </label>
                    <input id="asal_instansi" name="asal_instansi" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-2xs transition-all outline-none focus:border-teal-600 focus:ring-3 focus:ring-teal-600/15" value="{{ old('asal_instansi', $user->asal_instansi) }}" placeholder="Contoh: Universitas Lambung Mangkurat" required />
                    <p class="text-[11px] text-gray-400">Tulis nama lengkap instansi agar Dosen dapat menemukan data Anda.</p>
                    <x-input-error class="mt-1" :messages="$errors->get('asal_instansi')" />
                </div>

                <!-- Jurusan -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm leading-none font-medium text-gray-700 select-none" for="major">
                        <span>{{ __('Jurusan / Program Studi') }}</span> <span class="text-red-500">*</span>
                    </label>
                    <input id="major" name="major" type="text" class="w-full min-w-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-2xs transition-all outline-none focus:border-teal-600 focus:ring-3 focus:ring-teal-600/15" value="{{ old('major', $user->major) }}" placeholder="Contoh: Teknik Informatika" required />
                    <p class="text-[11px] text-gray-400">Menentukan posisi magang yang bisa Anda lamar.</p>
                    <x-input-error class="mt-1" :messages="$errors->get('major')" />
                </div>

                <!-- Pemilihan Pembimbing Sekolah -->
                <div class="md:col-span-2 mt-2 p-5 bg-teal-50/60 rounded-xl border border-teal-100">
                    <label class="flex items-center gap-2 text-sm font-bold text-teal-900 select-none mb-2" for="pembimbing_sekolah_id">
                        <i class="fas fa-user-tie text-teal-600"></i>
                        <span>{{ __('Pilih Dosen / Pembimbing Kampus Anda (Opsional)') }}</span>
                    </label>
                    
                    @if(isset($pembimbings) && count($pembimbings) > 0)
                        <select id="pembimbing_sekolah_id" name="pembimbing_sekolah_id" class="w-full min-w-0 rounded-lg border border-teal-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-2xs focus:border-teal-600 focus:ring-3 focus:ring-teal-600/15">
                            <option value="">-- Belum ada / Menyusul --</option>
                            @foreach($pembimbings as $pembimbing)
                                <option value="{{ $pembimbing->id }}" {{ old('pembimbing_sekolah_id', $user->pembimbing_sekolah_id) == $pembimbing->id ? 'selected' : '' }}>
                                    {{ $pembimbing->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-teal-700 mt-2"><i class="fas fa-check-circle mr-1"></i> Dengan memilih dosen pembimbing, beliau dapat memantau aktivitas magang (Logbook & Absensi) Anda secara real-time.</p>
                    @else
                        <select id="pembimbing_sekolah_id" name="pembimbing_sekolah_id" class="w-full min-w-0 rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-500 shadow-2xs" disabled>
                            <option value="">Tidak ada pembimbing yang terdaftar dari institusi Anda.</option>
                        </select>
                        <p class="text-xs text-red-500 mt-2 font-medium"><i class="fas fa-exclamation-circle mr-1"></i> Pastikan nama Asal Kampus Anda benar. Jika sudah benar namun tetap kosong, berarti dosen pembimbing Anda belum membuat akun.</p>
                    @endif
                    
                    <x-input-error class="mt-2" :messages="$errors->get('pembimbing_sekolah_id')" />
                </div>
            </div>
        </div>
        @endif

        <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3 mt-6">
            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-50 border border-teal-200 text-teal-700 text-xs font-semibold shadow-2xs">
                    <i class="fas fa-check-circle text-teal-600"></i>
                    <span>{{ __('Perubahan berhasil disimpan') }}</span>
                </div>
            @endif

            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium transition-all focus:outline-none focus:ring-2 focus:ring-teal-600/20 text-white h-9 px-5 py-2 bg-teal-600 hover:bg-teal-700 active:scale-95 shadow-xs cursor-pointer">
                <i class="fas fa-save text-xs"></i>
                <span>{{ __('Save Changes') }}</span>
            </button>
        </div>
    </form>
</section>

@push('scripts')
    <script>
        function previewPhoto(event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function(){
                var dataURL = reader.result;
                var output = document.getElementById('profile-preview');
                output.src = dataURL;
            };
            if(input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush