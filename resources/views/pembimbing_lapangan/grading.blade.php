<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-award text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Penilaian Akhir Magang') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div>
                <a href="{{ route('pembimbing_lapangan.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 overflow-hidden">
                
                {{-- Header Identitas Mahasiswa --}}
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-teal-50 dark:bg-teal-950/60 flex items-center justify-center text-teal-600 dark:text-teal-400 text-2xl border border-teal-200 dark:border-teal-800/60 flex-shrink-0 shadow-xs">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-800 dark:text-gray-100">{{ $app->user->name }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium flex items-center gap-2 mt-0.5">
                            <span><i class="far fa-envelope mr-1"></i> {{ $app->user->email }}</span>
                            <span>•</span>
                            <span class="text-teal-600 dark:text-teal-400 font-bold"><i class="fas fa-briefcase mr-1"></i> {{ $app->position->judul_posisi }}</span>
                        </p>
                    </div>
                </div>

                <div class="p-6 sm:p-8" x-data="{ 
                    disiplin: {{ old('nilai_disiplin', $app->nilai_disiplin ?? 0) }},
                    kinerja: {{ old('nilai_kinerja', $app->nilai_kinerja ?? 0) }},
                    get total() {
                        let d = parseFloat(this.disiplin) || 0;
                        let k = parseFloat(this.kinerja) || 0;
                        return Math.round((d * 0.4) + (k * 0.6));
                    },
                    get grade() {
                        let t = this.total;
                        if (t >= 85) return 'A (Sangat Baik)';
                        if (t >= 75) return 'B (Baik)';
                        if (t >= 65) return 'C (Cukup)';
                        if (t >= 50) return 'D (Kurang)';
                        return 'E (Gagal)';
                    }
                }">
                    <form action="{{ route('pembimbing_lapangan.grading.store', $app->id) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        {{-- Container Input Nilai --}}
                        <div class="bg-gray-50 dark:bg-gray-900/60 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                {{-- Nilai Disiplin --}}
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center justify-between">
                                        <span>Nilai Disiplin (0-100)</span>
                                        <span class="text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 px-2 py-0.5 rounded-md text-[10px] font-black border border-teal-200 dark:border-teal-800/60">Bobot 40%</span>
                                    </label>
                                    <input type="number" name="nilai_disiplin" min="0" max="100" required 
                                        x-model="disiplin"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 transition shadow-xs text-base font-bold font-mono" 
                                        placeholder="0 - 100">
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500">*Berdasarkan kedisiplinan dan absensi.</p>
                                </div>

                                {{-- Nilai Kinerja --}}
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center justify-between">
                                        <span>Nilai Kinerja (0-100)</span>
                                        <span class="text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/60 px-2 py-0.5 rounded-md text-[10px] font-black border border-teal-200 dark:border-teal-800/60">Bobot 60%</span>
                                    </label>
                                    <input type="number" name="nilai_kinerja" min="0" max="100" required 
                                        x-model="kinerja"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-teal-500 focus:border-teal-500 transition shadow-xs text-base font-bold font-mono" 
                                        placeholder="0 - 100">
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500">*Berdasarkan pencapaian tugas & jurnal.</p>
                                </div>

                            </div>

                            {{-- Live Estimation Card --}}
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700/80 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950/60 flex items-center justify-center text-teal-600 dark:text-teal-400 text-lg border border-teal-200 dark:border-teal-800/60 flex-shrink-0">
                                        <i class="fas fa-calculator"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Kalkulasi Nilai Akhir</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Prediksi Predikat: <span class="font-bold text-teal-600 dark:text-teal-400" x-text="grade"></span></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-2xl font-black text-gray-800 dark:text-gray-100 font-mono" x-text="total">0</span>
                                    <span class="text-xs text-gray-400 dark:text-gray-500 font-bold">/ 100</span>
                                </div>
                            </div>
                        </div>

                        {{-- Catatan Evaluasi --}}
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Catatan Evaluasi Pembimbing Lapangan</label>
                            <textarea name="catatan_pembimbing_lapangan" rows="4" 
                                class="w-full p-4 rounded-2xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-teal-500 focus:border-teal-500 transition shadow-xs text-xs sm:text-sm font-medium"
                                placeholder="Tuliskan catatan evaluasi, apresiasi, atau masukan untuk peserta magang...">{{ old('catatan_pembimbing_lapangan', $app->catatan_pembimbing_lapangan) }}</textarea>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                            <a href="{{ route('pembimbing_lapangan.dashboard') }}" class="w-full sm:w-auto text-center px-6 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400 font-bold hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-800 dark:hover:text-gray-100 transition text-xs">
                                Batal
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-7 py-2.5 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-md transition flex items-center justify-center gap-2 active:scale-95 text-xs uppercase tracking-wider">
                                <i class="fas fa-save mr-1"></i> Simpan Penilaian Akhir
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>