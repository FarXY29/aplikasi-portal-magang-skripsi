<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-clipboard-check text-teal-600 dark:text-teal-400"></i>
                {{ __('Penilaian Akhir Magang') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('pembimbing_lapangan.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-sm">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-950/40 flex items-center justify-center text-teal-600 dark:text-teal-400 text-xl border border-teal-100 dark:border-teal-900/40">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $app->user->name }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $app->user->email }}</p>
                    </div>
                </div>

                <div class="p-8">
                    <form action="{{ route('pembimbing_lapangan.grading.store', $app->id) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-700">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center justify-between">
                                    <span>Nilai Disiplin (0-100)</span>
                                    <span class="text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/50 px-2 py-0.5 rounded text-xs font-bold border border-teal-100 dark:border-teal-900/40">Bobot 40%</span>
                                </label>
                                <input type="number" name="nilai_disiplin" min="0" max="100" required 
                                    value="{{ old('nilai_disiplin', $app->nilai_disiplin) }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm text-sm font-medium" 
                                    placeholder="Contoh: 85">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Berdasarkan kehadiran dan kepatuhan aturan.</p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center justify-between">
                                    <span>Nilai Kinerja (0-100)</span>
                                    <span class="text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/50 px-2 py-0.5 rounded text-xs font-bold border border-teal-100 dark:border-teal-900/40">Bobot 60%</span>
                                </label>
                                <input type="number" name="nilai_kinerja" min="0" max="100" required 
                                    value="{{ old('nilai_kinerja', $app->nilai_kinerja) }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm text-sm font-medium" 
                                    placeholder="Contoh: 90">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Berdasarkan penyelesaian tugas dan output kerja.</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Catatan Pembimbing Lapangan</label>
                            <textarea name="catatan_pembimbing_lapangan" rows="4" 
                                class="w-full p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm text-sm font-medium"
                                placeholder="Tuliskan catatan evaluasi atau masukan untuk peserta...">{{ old('catatan_pembimbing_lapangan', $app->catatan_pembimbing_lapangan) }}</textarea>
                        </div>

                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                            <a href="{{ route('pembimbing_lapangan.dashboard') }}" class="w-full sm:w-auto text-center px-6 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-800 dark:hover:text-gray-100 transition text-sm">
                                Batal
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-7 py-2.5 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-lg shadow-teal-500/20 transition transform active:scale-95 text-sm flex items-center justify-center gap-2">
                                <i class="fas fa-save"></i> Simpan Penilaian Akhir
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>