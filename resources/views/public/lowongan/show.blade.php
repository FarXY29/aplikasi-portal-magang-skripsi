<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Lowongan Magang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $position->judul_posisi }}</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $position->instansi->nama_dinas ?? 'Instansi Tidak Diketahui' }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Kuota Available</span>
                        <p class="text-lg font-bold text-indigo-600">{{ $position->kuota }} Posisi</p>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Jurusan Dicari</span>
                        <p class="text-md font-medium text-gray-800 dark:text-gray-200">{{ $position->required_major ?? 'Semua Jurusan' }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Deskripsi Pekerjaan & Persyaratan</h3>
                    <div class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                        {{ $position->deskripsi }}
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t pt-4">
                    <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-200 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 transition">
                        Kembali
                    </a>
                    @auth
                        @if(auth()->user()->role === 'peserta')
                            <a href="{{ route('peserta.daftar.form', $position->id) }}" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
                                Daftar Sekarang
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
                            Login untuk Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
