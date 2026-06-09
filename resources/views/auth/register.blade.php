<x-guest-layout>
    <div class="flex flex-col md:flex-row gap-6 max-w-7xl mx-auto my-8 px-4 sm:px-6">
        
        <div class="w-full md:w-5/12 bg-teal-600 rounded-3xl shadow-xl overflow-hidden relative flex flex-col justify-between p-8 md:p-12 min-h-[400px]">
            
            <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-12 -ml-12 w-64 h-64 bg-teal-800 opacity-20 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <a href="{{ route('home') }}" class="group inline-flex items-center text-sm font-bold text-teal-100 hover:text-white transition">
                    <div class="w-10 h-10 rounded-full bg-teal-700/50 flex items-center justify-center mr-3 group-hover:bg-teal-500 transition shadow-sm border border-teal-500/30">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </div>
                    Kembali ke Beranda
                </a>
            </div>

            <div class="relative z-10 mt-10 md:mt-0">
                <div class="w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-md border border-white/20 shadow-inner">
                    <x-application-logo class="w-12 h-12 fill-current text-white" />
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight text-white mb-4 drop-shadow-md">
                    SiMagang
                </h1>
                <p class="text-teal-50 text-lg font-medium leading-relaxed opacity-90">
                    Platform resmi Pemerintah Kota Banjarmasin. Mulai perjalanan karir profesional Anda bersama kami.
                </p>
            </div>

            <div class="relative z-10 mt-12">
                <p class="text-xs text-teal-200/60 font-medium">
                    &copy; {{ date('Y') }} Diskominfotik Banjarmasin.
                </p>
            </div>
        </div>

        <div class="w-full md:w-7/12 bg-white rounded-3xl shadow-xl overflow-hidden p-8 md:p-12 border border-gray-100">
            
            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900">Buat Akun Baru</h2>
                <p class="mt-2 text-sm text-gray-500" id="form-description">
                    Silakan pilih peran Anda dan lengkapi formulir pendaftaran.
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5" id="registerForm">
                @csrf

                <!-- Pilihan Role -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Mendaftar Sebagai</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="role" value="peserta" class="peer sr-only" checked onchange="toggleRoleFields('peserta')">
                            <div class="rounded-xl border-2 border-gray-200 px-4 py-3 hover:bg-gray-50 peer-checked:border-teal-500 peer-checked:bg-teal-50 peer-checked:text-teal-700 transition">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold">Peserta Magang</span>
                                    <i class="fas fa-user-graduate text-teal-500 opacity-0 peer-checked:opacity-100"></i>
                                </div>
                            </div>
                        </label>

                        <label class="cursor-pointer relative">
                            <input type="radio" name="role" value="pembimbing" class="peer sr-only" onchange="toggleRoleFields('pembimbing')" {{ old('role') == 'pembimbing' ? 'checked' : '' }}>
                            <div class="rounded-xl border-2 border-gray-200 px-4 py-3 hover:bg-gray-50 peer-checked:border-teal-500 peer-checked:bg-teal-50 peer-checked:text-teal-700 transition">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold">Pembimbing Sekolah</span>
                                    <i class="fas fa-chalkboard-teacher text-teal-500 opacity-0 peer-checked:opacity-100"></i>
                                </div>
                            </div>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('role')" class="mt-1" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5 ml-1">Nama Lengkap</label>
                    <input id="name" name="name" type="text" required autofocus
                        class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm bg-gray-50 focus:bg-white transition"
                        placeholder="Sesuai KTP/KTM" value="{{ old('name') }}">
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5 ml-1">Username</label>
                        <input id="username" name="username" type="text" required
                            class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm bg-gray-50 focus:bg-white transition"
                            placeholder="Username unik" value="{{ old('username') }}">
                        <x-input-error :messages="$errors->get('username')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5 ml-1">Email</label>
                        <input id="email" name="email" type="email" required
                            class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm bg-gray-50 focus:bg-white transition"
                            placeholder="Email aktif" value="{{ old('email') }}">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                </div>

                <!-- Field Khusus Peserta -->
                <div id="field-peserta" class="{{ old('role') == 'pembimbing' ? 'hidden' : 'block' }}">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5 ml-1">Jurusan / Program Studi</label>
                    <input id="major" name="major" type="text"
                        class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm bg-gray-50 focus:bg-white transition"
                        placeholder="Contoh: Teknik Informatika" value="{{ old('major') }}">
                    <x-input-error :messages="$errors->get('major')" class="mt-1" />
                </div>

                <!-- Field Khusus Pembimbing -->
                <div id="field-pembimbing" class="{{ old('role') == 'pembimbing' ? 'block' : 'hidden' }}">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5 ml-1">Asal Sekolah / Kampus</label>
                    <input id="asal_instansi" name="asal_instansi" type="text"
                        class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm bg-gray-50 focus:bg-white transition"
                        placeholder="Contoh: Universitas Lambung Mangkurat" value="{{ old('asal_instansi') }}">
                    <x-input-error :messages="$errors->get('asal_instansi')" class="mt-1" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5 ml-1">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm bg-gray-50 focus:bg-white transition"
                            placeholder="Min. 8 karakter">
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5 ml-1">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm bg-gray-50 focus:bg-white transition"
                            placeholder="Ulangi password">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg shadow-teal-200 text-sm font-extrabold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition transform hover:-translate-y-0.5">
                        DAFTAR SEKARANG
                    </button>
                </div>

                <div class="text-center pt-2">
                    <p class="text-sm text-gray-500">
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

                    if (role === 'pembimbing') {
                        fieldPeserta.classList.add('hidden');
                        fieldPembimbing.classList.remove('hidden');
                        inputInstansi.setAttribute('required', 'required');
                        inputMajor.removeAttribute('required');
                        desc.textContent = "Lengkapi formulir di bawah untuk mendaftar sebagai pembimbing sekolah/kampus.";
                    } else {
                        fieldPeserta.classList.remove('hidden');
                        fieldPembimbing.classList.add('hidden');
                        inputMajor.setAttribute('required', 'required');
                        inputInstansi.removeAttribute('required');
                        desc.textContent = "Lengkapi formulir di bawah untuk mendaftar sebagai peserta magang.";
                    }
                }
                
                // Initialize on load
                document.addEventListener('DOMContentLoaded', () => {
                    const selectedRole = document.querySelector('input[name="role"]:checked');
                    if(selectedRole) toggleRoleFields(selectedRole.value);
                });
            </script>


        </div>
    </div>
</x-guest-layout>