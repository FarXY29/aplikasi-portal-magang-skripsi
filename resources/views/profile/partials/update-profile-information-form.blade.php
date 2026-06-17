<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Perbarui informasi profil, username, dan data akademik Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" enctype="multipart/form-data" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Foto Profil dengan Preview Langsung -->
        <div class="mb-6">
            <div class="flex items-center gap-6">
                <div class="flex-shrink-0">
                    @if ($user->photo && \Illuminate\Support\Facades\Storage::exists('public/' . $user->photo))
                        <img id="profile-preview" src="{{ asset('storage/' . $user->photo) }}" alt="Foto Profil" class="h-24 w-24 object-cover rounded-full border-2 border-teal-200 shadow-sm">
                    @else
                        <img id="profile-preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=0D9488&background=CCFBF1" alt="Foto Profil Default" class="h-24 w-24 object-cover rounded-full border-2 border-teal-200 shadow-sm">
                    @endif
                </div>

                <div class="flex-grow">
                    <x-input-label for="photo" :value="__('Foto Profil (Kartu Tanda Pengenal)')" />
                    <input id="photo" name="photo" type="file" 
                           onchange="previewPhoto(event)"
                           class="mt-1 block w-full border border-gray-300 rounded p-1.5 text-sm text-gray-500 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" accept="image/*" />
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB. Disarankan pas foto formal atau rapi.</p>
                    <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                </div>
            </div>
        </div>

        <!-- Nama -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autocomplete="username" placeholder="username_anda" />
            <p class="text-xs text-gray-500 mt-1">Digunakan untuk login alternatif selain email.</p>
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}
                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Kirim ulang verifikasi.') }}
                        </button>
                    </p>
                </div>
            @endif
        </div>

        @if ($user->role === 'pembimbing_lapangan' || $user->role === 'dinas')
        <div class="mb-4">
            <x-input-label for="signature" :value="__('Upload Tanda Tangan / Paraf (Format: PNG/JPG, Transparan lebih baik)')" />
                <input id="signature" name="signature" type="file" class="mt-1 block w-full border border-gray-300 rounded p-1" accept="image/*" />
                    @if ($user->signature)
                <div class="mt-2">
                    <p class="text-sm text-gray-500">Tanda Tangan Saat Ini:</p>
                        <img src="{{ asset('storage/' . $user->signature) }}" alt="Signature" class="h-16 mt-1 border rounded p-1">                        </div>
                @endif
            <x-input-error class="mt-2" :messages="$errors->get('signature')" />
        </div>
        @endif

        @if ($user->role === 'pembimbing')
        <!-- NIDN / NIP -->
        <div>
            <x-input-label for="nik" :value="__('NIDN / NIP / NIK')" />
            <x-text-input id="nik" name="nik" type="text" class="mt-1 block w-full" :value="old('nik', $user->nik)" placeholder="Nomor Induk Dosen / Pegawai" />
            <x-input-error class="mt-2" :messages="$errors->get('nik')" />
        </div>

        <!-- Asal Instansi -->
        <div>
            <x-input-label for="asal_instansi" :value="__('Asal Sekolah / Universitas')" />
            <x-text-input id="asal_instansi" name="asal_instansi" type="text" class="mt-1 block w-full" :value="old('asal_instansi', $user->asal_instansi)" placeholder="Contoh: Universitas Lambung Mangkurat" required />
            <p class="text-xs text-teal-600 mt-1 font-bold">Penting! Ketikkan nama kampus persis dengan yang diketik mahasiswa agar mereka bisa menemukan nama Anda di pilihan pembimbing.</p>
            <x-input-error class="mt-2" :messages="$errors->get('asal_instansi')" />
        </div>

        <!-- No HP -->
        <div>
            <x-input-label for="phone" :value="__('Nomor WhatsApp')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" placeholder="Contoh: 0812xxxx" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>
        @endif

        @if ($user->role === 'peserta')
        <!-- NIM / NPM -->
        <div>
            <x-input-label for="nik" :value="__('NIM / NPM')" />
            <x-text-input id="nik" name="nik" type="text" class="mt-1 block w-full" :value="old('nik', $user->nik)" placeholder="Nomor Induk Mahasiswa" />
            <p class="text-xs text-gray-500 mt-1">Nomor ini akan tercetak di sertifikat magang.</p>
            <x-input-error class="mt-2" :messages="$errors->get('nik')" />
        </div>

        <!-- Asal Instansi -->
        <div>
            <x-input-label for="asal_instansi" :value="__('Asal Sekolah / Universitas')" />
            <x-text-input id="asal_instansi" name="asal_instansi" type="text" class="mt-1 block w-full" :value="old('asal_instansi', $user->asal_instansi)" placeholder="Contoh: Universitas Lambung Mangkurat" required />
            <p class="text-xs text-gray-500 mt-1">Tulis nama lengkap instansi agar Pembimbing/Dosen dapat menemukan data Anda.</p>
            <x-input-error class="mt-2" :messages="$errors->get('asal_instansi')" />
        </div>


        <!-- Jurusan -->
        <div>
            <x-input-label for="major" :value="__('Jurusan / Program Studi')" />
            <x-text-input id="major" name="major" type="text" class="mt-1 block w-full" :value="old('major', $user->major)" placeholder="Contoh: Teknik Informatika" required />
            <p class="text-xs text-gray-500 mt-1">Penting! Jurusan menentukan posisi magang yang bisa Anda lamar.</p>
            <x-input-error class="mt-2" :messages="$errors->get('major')" />
        </div>

        <!-- No HP -->
        <div>
            <x-input-label for="phone" :value="__('Nomor WhatsApp')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" placeholder="Contoh: 0812xxxx" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- Pemilihan Pembimbing Sekolah -->
        <div class="mt-4 p-4 bg-teal-50 rounded-xl border border-teal-100">
            <x-input-label for="pembimbing_sekolah_id" :value="__('Pilih Dosen / Pembimbing Kampus Anda (Opsional)')" class="font-bold text-teal-800" />
            
            @if(isset($pembimbings) && count($pembimbings) > 0)
                <select id="pembimbing_sekolah_id" name="pembimbing_sekolah_id" class="mt-2 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                    <option value="">-- Belum ada / Menyusul --</option>
                    @foreach($pembimbings as $pembimbing)
                        <option value="{{ $pembimbing->id }}" {{ old('pembimbing_sekolah_id', $user->pembimbing_sekolah_id) == $pembimbing->id ? 'selected' : '' }}>
                            {{ $pembimbing->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-teal-600 mt-2">Dengan memilih dosen pembimbing, beliau dapat memantau aktivitas magang (Logbook & Absensi) Anda secara real-time.</p>
            @else
                <select id="pembimbing_sekolah_id" name="pembimbing_sekolah_id" class="mt-2 block w-full border-gray-300 bg-gray-100 rounded-md shadow-sm text-gray-500" disabled>
                    <option value="">Tidak ada pembimbing yang terdaftar dari institusi Anda.</option>
                </select>
                <p class="text-xs text-red-500 mt-2">Pastikan nama Asal Kampus Anda benar. Jika sudah benar namun tetap kosong, berarti dosen pembimbing Anda belum membuat akun.</p>
            @endif
            
            <x-input-error class="mt-2" :messages="$errors->get('pembimbing_sekolah_id')" />
        </div>
        @endif
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Tersimpan.') }}</p>
            @endif
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