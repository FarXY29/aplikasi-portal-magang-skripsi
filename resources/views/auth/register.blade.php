<x-guest-layout>
    <div class="flex flex-col md:flex-row gap-4 max-w-5xl mx-auto my-auto px-2 sm:px-6">
        
        <div class="w-full md:w-5/12 bg-teal-600 rounded-2xl sm:rounded-3xl shadow-xl overflow-hidden relative flex flex-col justify-between p-6 sm:p-8 min-h-[160px] md:min-h-[420px]">
            
            <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-white dark:bg-gray-800 opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-12 -ml-12 w-64 h-64 bg-teal-800 opacity-20 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <a href="{{ route('home') }}" class="group inline-flex items-center text-xs sm:text-sm font-bold text-teal-100 hover:text-white transition">
                    <div class="w-8 h-8 rounded-full bg-teal-700/50 flex items-center justify-center mr-2.5 group-hover:bg-teal-500 transition shadow-sm border border-teal-500/30">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Beranda
                </a>
            </div>

            <div class="relative z-10 mt-6 md:mt-0">
                <div class="w-14 h-14 bg-white dark:bg-gray-800/10 rounded-2xl flex items-center justify-center mb-4 backdrop-blur-md border border-white/20 shadow-inner">
                    <x-application-logo class="w-8 h-8 fill-current text-white" />
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-2 drop-shadow-md">
                    SiMagang
                </h1>
                <p class="text-teal-50 text-xs sm:text-sm font-medium leading-relaxed opacity-90">
                    Platform resmi Pemerintah Kota Banjarmasin. Mulai perjalanan karir profesional Anda bersama kami.
                </p>
            </div>

            <div class="relative z-10 mt-6 hidden md:block">
                <p class="text-[11px] text-teal-200/60 font-medium">
                    &copy; {{ date('Y') }} Diskominfotik Banjarmasin.
                </p>
            </div>
        </div>

        <div class="w-full md:w-7/12 bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-xl overflow-hidden p-5 sm:p-7 border border-gray-100 dark:border-gray-700 flex flex-col justify-center">
            
            <div class="mb-4">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 leading-tight">Buat Akun Baru</h2>
                <p class="mt-1 text-xs sm:text-sm text-gray-500 dark:text-gray-400" id="form-description">
                    Silakan pilih peran Anda dan lengkapi formulir pendaftaran.
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-3" id="registerForm">
                @csrf

                <!-- Pilihan Role -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5 ml-1">Mendaftar Sebagai</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="role" value="peserta" class="peer sr-only" checked onchange="toggleRoleFields('peserta')">
                            <div class="rounded-xl border-2 border-gray-200 dark:border-gray-700 px-3.5 py-2 hover:bg-gray-50 dark:hover:bg-gray-900 peer-checked:border-teal-500 peer-checked:bg-teal-50 peer-checked:text-teal-700 transition">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold">Peserta Magang</span>
                                    <i class="fas fa-user-graduate text-teal-500 opacity-0 peer-checked:opacity-100 text-xs"></i>
                                </div>
                            </div>
                        </label>

                        <label class="cursor-pointer relative">
                            <input type="radio" name="role" value="pembimbing" class="peer sr-only" onchange="toggleRoleFields('pembimbing')" {{ old('role') == 'pembimbing' ? 'checked' : '' }}>
                            <div class="rounded-xl border-2 border-gray-200 dark:border-gray-700 px-3.5 py-2 hover:bg-gray-50 dark:hover:bg-gray-900 peer-checked:border-teal-500 peer-checked:bg-teal-50 peer-checked:text-teal-700 transition">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold">Pembimbing Sekolah</span>
                                    <i class="fas fa-chalkboard-teacher text-teal-500 opacity-0 peer-checked:opacity-100 text-xs"></i>
                                </div>
                            </div>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('role')" class="mt-1" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase mb-1 ml-1">Nama Lengkap</label>
                        <input id="name" name="name" type="text" required autofocus
                            class="block w-full px-3.5 py-2 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-xs sm:text-sm bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 transition font-medium"
                            placeholder="Sesuai KTP/KTM" value="{{ old('name') }}">
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase mb-1 ml-1">Username</label>
                        <input id="username" name="username" type="text" required
                            class="block w-full px-3.5 py-2 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-xs sm:text-sm bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 transition font-medium"
                            placeholder="Username unik" value="{{ old('username') }}">
                        <x-input-error :messages="$errors->get('username')" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase mb-1 ml-1">Email</label>
                        <input id="email" name="email" type="email" required
                            class="block w-full px-3.5 py-2 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-xs sm:text-sm bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 transition font-medium"
                            placeholder="Email aktif" value="{{ old('email') }}">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                    <div>
                        <!-- Field Khusus Peserta -->
                        <div id="field-peserta" class="{{ old('role') == 'pembimbing' ? 'hidden' : 'block' }}">
                            <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase mb-1 ml-1">Jurusan / Program Studi</label>
                            <input id="major" name="major" type="text"
                                class="block w-full px-3.5 py-2 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-xs sm:text-sm bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 transition font-medium"
                                placeholder="Contoh: Teknik Informatika" value="{{ old('major') }}">
                            <x-input-error :messages="$errors->get('major')" class="mt-1" />
                        </div>
                        <!-- Field Khusus Pembimbing -->
                        <div id="field-pembimbing" class="{{ old('role') == 'pembimbing' ? 'block' : 'hidden' }}">
                            <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase mb-1 ml-1">Asal Sekolah / Kampus</label>
                            <input id="asal_instansi" name="asal_instansi" type="text"
                                class="block w-full px-3.5 py-2 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-xs sm:text-sm bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 transition font-medium"
                                placeholder="Contoh: Univ. Lambung Mangkurat" value="{{ old('asal_instansi') }}">
                            <x-input-error :messages="$errors->get('asal_instansi')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase mb-1 ml-1">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="block w-full px-3.5 py-2 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-xs sm:text-sm bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 transition font-medium"
                            placeholder="Min. 8 karakter">
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase mb-1 ml-1">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="block w-full px-3.5 py-2 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-xs sm:text-sm bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 transition font-medium"
                            placeholder="Ulangi password">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>
                </div>

                <div class="pt-1.5">
                    <button type="submit" class="w-full flex justify-center items-center py-2.5 px-5 border border-transparent rounded-xl shadow-md shadow-teal-200 text-xs sm:text-sm font-extrabold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition transform hover:-translate-y-0.5 tracking-wide">
                        DAFTAR SEKARANG <i class="fas fa-user-plus ml-2"></i>
                    </button>
                </div>

                <div class="relative my-3.5">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-3 bg-white dark:bg-gray-800 text-gray-400 dark:text-gray-500 text-[11px] font-medium uppercase tracking-wider">Atau daftar dengan</span>
                    </div>
                </div>

                <div>
                    <a id="googleRegisterBtn" href="{{ route('google.login', ['role' => 'peserta']) }}" class="flex items-center justify-center w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:border-gray-700 rounded-xl shadow-xs text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 hover:border-gray-400 transition transform hover:-translate-y-0.5">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-4 w-4 sm:h-4.5 sm:w-4.5 mr-2.5" alt="Google">
                        <span id="googleRegisterText">Daftar sebagai Peserta Magang dengan Google</span>
                    </a>
                </div>

                <div class="text-center pt-2.5 border-t border-gray-100 dark:border-gray-700 mt-3.5">
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-bold text-teal-600 hover:text-teal-800 hover:underline transition">
                            Masuk di sini
                        </a>
                    </p>
                </div>
            </form>

            <script>
                function toggleRoleFields(role) {
                    const fieldPeserta = document.getElementById('field-peserta');
                    const fieldPembimbing = document.getElementById('field-pembimbing');
                    const inputMajor = document.getElementById('major');
                    const inputInstansi = document.getElementById('asal_instansi');
                    const desc = document.getElementById('form-description');
                    const googleBtn = document.getElementById('googleRegisterBtn');
                    const googleText = document.getElementById('googleRegisterText');

                    if (role === 'pembimbing') {
                        fieldPeserta.classList.add('hidden');
                        fieldPembimbing.classList.remove('hidden');
                        inputInstansi.setAttribute('required', 'required');
                        inputMajor.removeAttribute('required');
                        desc.textContent = "Lengkapi formulir di bawah untuk mendaftar sebagai pembimbing sekolah/kampus.";
                        if (googleBtn && googleText) {
                            googleBtn.href = "{{ route('google.login') }}?role=pembimbing";
                            googleText.textContent = "Daftar sebagai Pembimbing Sekolah dengan Google";
                        }
                    } else {
                        fieldPeserta.classList.remove('hidden');
                        fieldPembimbing.classList.add('hidden');
                        inputMajor.setAttribute('required', 'required');
                        inputInstansi.removeAttribute('required');
                        desc.textContent = "Lengkapi formulir di bawah untuk mendaftar sebagai peserta magang.";
                        if (googleBtn && googleText) {
                            googleBtn.href = "{{ route('google.login') }}?role=peserta";
                            googleText.textContent = "Daftar sebagai Peserta Magang dengan Google";
                        }
                    }
                }
                
                // Initialize on load
                document.addEventListener('DOMContentLoaded', () => {
                    const selectedRole = document.querySelector('input[name="role"]:checked');
                    if(selectedRole) toggleRoleFields(selectedRole.value);

                    const googleBtn = document.getElementById('googleRegisterBtn');
                    if (googleBtn) {
                        googleBtn.addEventListener('click', function(e) {
                            const currentRole = document.querySelector('input[name="role"]:checked');
                            const roleVal = currentRole ? currentRole.value : 'peserta';
                            this.href = "{{ route('google.login') }}?role=" + roleVal;
                        });
                    }
                });
            </script>

        </div>
    </div>
</x-guest-layout>