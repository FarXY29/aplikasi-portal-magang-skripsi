<x-app-layout>
    @push('styles')
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    @endpush

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-teal-600 dark:bg-teal-500 text-white flex items-center justify-center shadow-sm">
                <i class="fas fa-plus text-sm"></i>
            </div>
            <div>
                <h2 class="font-black text-xl text-gray-900 dark:text-gray-100 leading-tight">Tambah Rumpun Keilmuan</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Buat kategori rumpun keahlian baru untuk pengelompokan program studi</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6 font-[Inter]">
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.master.major-categories.index') }}" class="group flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                    <i class="fas fa-arrow-left text-xs text-gray-400 group-hover:text-teal-600"></i>
                </div>
                Kembali ke Daftar Rumpun
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 md:p-8">
            <form action="{{ route('admin.master.major-categories.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nama Rumpun -->
                <div>
                    <label for="name" class="block text-xs font-black uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                        Nama Rumpun Keilmuan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Teknologi Informasi & Rekayasa Komputer" class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:border-teal-500 focus:ring-teal-500 py-2.5 px-3 shadow-xs">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kode Singkatan -->
                <div>
                    <label for="code" class="block text-xs font-black uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                        Kode Singkatan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="code" name="code" value="{{ old('code') }}" required placeholder="Contoh: TIK, EKBIS, TEKNIK, HUKUM_AP" class="w-full text-sm font-mono uppercase rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:border-teal-500 focus:ring-teal-500 py-2.5 px-3 shadow-xs">
                    @error('code')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-xs font-black uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                        Deskripsi Cakupan Keilmuan
                    </label>
                    <textarea id="description" name="description" rows="3" placeholder="Penjelasan singkat bidang ilmu dan keahlian yang tercakup dalam rumpun ini..." class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:border-teal-500 focus:ring-teal-500 py-2.5 px-3 shadow-xs">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3">
                    <a href="{{ route('admin.master.major-categories.index') }}" class="px-5 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-bold text-xs hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-xs uppercase tracking-wider transition shadow-sm">
                        <i class="fas fa-save mr-1.5"></i> Simpan Rumpun
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
