<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-cogs text-teal-600"></i>
                {{ __('Pengaturan Sistem') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition group">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="flex items-center p-4 mb-6 text-teal-800 rounded-xl bg-teal-50 border border-teal-100 shadow-sm relative">
                    <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 mr-3 text-teal-600"></i>
                    <div class="text-sm font-bold">
                        {{ session('success') }}
                    </div>
                    <button @click="show = false" type="button" class="ml-auto bg-teal-100 text-teal-500 rounded-lg p-1.5 hover:bg-teal-200 inline-flex h-8 w-8 items-center justify-center transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                
                <div class="space-y-8">
                    
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
                        <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 shadow-inner">
                                <i class="fas fa-laptop-code text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Identitas Aplikasi</h3>
                                <p class="text-sm text-gray-500">Konfigurasi nama dan branding dasar sistem.</p>
                            </div>
                        </div>
                        <div class="p-8">
                            <div class="max-w-2xl">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Aplikasi</label>
                                <div class="relative group">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition">
                                        <i class="fas fa-heading"></i>
                                    </span>
                                    <input type="text" name="app_name" value="{{ $settings['app_name'] ?? 'SiMagang Banjarmasin' }}" 
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                                        placeholder="Masukkan nama aplikasi...">
                                </div>
                                <p class="text-xs text-gray-400 mt-2 flex items-center">
                                    <i class="fas fa-info-circle mr-1.5"></i> Nama ini akan tampil di halaman login, title bar browser, dan footer.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100" 
                         x-data="{ announcement: '{{ addslashes($settings['announcement'] ?? '') }}' }">
                        
                        <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 shadow-inner">
                                <i class="fas fa-bullhorn text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Papan Pengumuman</h3>
                                <p class="text-sm text-gray-500">Informasi global untuk seluruh peserta magang.</p>
                            </div>
                        </div>
                        
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Isi Pengumuman</label>
                                <textarea name="announcement" x-model="announcement" rows="5" 
                                    class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition shadow-sm text-sm" 
                                    placeholder="Contoh: Pendaftaran magang periode Juli dibuka mulai tanggal..."></textarea>
                                <p class="text-xs text-gray-400 mt-2">
                                    Kosongkan jika tidak ada pengumuman.
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-4 border border-dashed border-gray-300 flex flex-col h-full">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 block text-center">Live Preview Dashboard</span>
                                
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r shadow-sm flex-grow">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-yellow-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700 font-medium">
                                                <span x-text="announcement ? announcement : 'Tidak ada pengumuman aktif saat ini.'"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="inline-flex items-center px-8 py-3 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-gray-800 hover:shadow-xl transition transform hover:-translate-y-0.5 active:scale-95">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>