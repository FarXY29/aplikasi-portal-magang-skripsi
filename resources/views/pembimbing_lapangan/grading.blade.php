<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Penilaian Akhir Magang
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('pembimbing_lapangan.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                </div>
                <div class="mb-6 border-b pb-4">
                    <h3 class="text-lg font-bold text-gray-900">{{ $app->user->name }}</h3>
                </div>

                <form action="{{ route('pembimbing_lapangan.grading.store', $app->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-5 rounded-2xl border border-gray-100 mb-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 flex justify-between">
                                <span>Nilai Disiplin (0-100)</span>
                                <span class="text-teal-600 bg-teal-50 px-2 py-0.5 rounded text-xs">Bobot 40%</span>
                            </label>
                            <input type="number" name="nilai_disiplin" min="0" max="100" required 
                                class="w-full rounded-xl border-gray-200 focus:ring-teal-500" placeholder="Contoh: 85">
                            <p class="text-xs text-gray-500">Berdasarkan kehadiran dan kepatuhan aturan.</p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 flex justify-between">
                                <span>Nilai Kinerja (0-100)</span>
                                <span class="text-teal-600 bg-teal-50 px-2 py-0.5 rounded text-xs">Bobot 60%</span>
                            </label>
                            <input type="number" name="nilai_kinerja" min="0" max="100" required 
                                class="w-full rounded-xl border-gray-200 focus:ring-teal-500" placeholder="Contoh: 90">
                            <p class="text-xs text-gray-500">Berdasarkan penyelesaian tugas dan output kerja.</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Catatan Pembimbing</label>
                        <textarea name="catatan_pembimbing_lapangan" rows="3" class="w-full rounded-xl border-gray-200"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-teal-600 text-white font-bold py-3 rounded-xl hover:bg-teal-700 transition">
                        Simpan Penilaian Akhir
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>