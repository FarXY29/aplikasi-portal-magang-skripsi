<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-950/60 flex items-center justify-center border border-teal-200 dark:border-teal-800/60">
                    <i class="fas fa-clipboard-check text-teal-600 dark:text-teal-400 text-lg"></i>
                </div>
                {{ __('Formulir Penilaian Akhir') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="mb-6 print:hidden">
                <a href="{{ route('pembimbing_lapangan.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-xs">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                {{-- Sidebar Identitas & Live Score --}}
                <div class="w-full lg:w-1/3 bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700 lg:sticky lg:top-8 space-y-6">
                    <div class="text-center">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center text-white font-black text-3xl mx-auto shadow-md mb-3 border-4 border-teal-50 dark:border-teal-900/50">
                            {{ strtoupper(substr($application->user->name, 0, 1)) }}
                        </div>
                        <h3 class="font-black text-gray-900 dark:text-gray-100 text-lg">{{ $application->user->name }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ $application->user->email }}</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-2xl border border-gray-200 dark:border-gray-700">
                            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Periode Magang</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200 text-xs">
                                {{ \Carbon\Carbon::parse($application->tanggal_mulai)->format('d M Y') }} — {{ \Carbon\Carbon::parse($application->tanggal_selesai)->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 dark:border-gray-700 text-center">
                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Rata-Rata Nilai Akhir</p>
                        <h2 class="text-5xl font-black text-teal-600 dark:text-teal-400 font-mono" id="avg-score">0</h2>
                        <span class="inline-block mt-3 px-3.5 py-1 bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 text-xs font-bold rounded-full" id="grade-label">-</span>
                    </div>
                </div>

                {{-- Formulir Input Kriteria --}}
                <div class="w-full lg:w-2/3 bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700">
                    <form action="{{ route('pembimbing_lapangan.simpan_nilai', $application->id) }}" method="POST" id="gradingForm">
                        @csrf
                        
                        <div class="mb-8">
                            <h3 class="font-bold text-gray-800 dark:text-gray-100 text-lg border-b border-gray-100 dark:border-gray-700 pb-3 mb-4 flex items-center gap-2">
                                <i class="fas fa-star text-amber-400"></i> Kriteria Penilaian Peserta
                            </h3>
                            <div class="text-xs text-blue-800 dark:text-blue-300 mb-6 bg-blue-50/60 dark:bg-blue-950/40 p-4 rounded-2xl border border-blue-200 dark:border-blue-800/60 flex items-start gap-2.5">
                                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5 text-sm flex-shrink-0"></i>
                                <span class="leading-relaxed font-medium">Isi nilai pada rentang <strong>0 - 100</strong>. Penilaian harus mencerminkan performa & kedisiplinan nyata peserta selama magang.</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                                @php
                                    $kriteria = [
                                        'nilai_kerajinan' => ['label' => 'Kerajinan', 'icon' => 'fa-briefcase'],
                                        'nilai_disiplin' => ['label' => 'Disiplin', 'icon' => 'fa-clock'],
                                        'nilai_adaptasi' => ['label' => 'Adaptasi', 'icon' => 'fa-sync-alt'],
                                        'nilai_kreatifitas' => ['label' => 'Kreatifitas', 'icon' => 'fa-lightbulb'],
                                        'nilai_skill_pengetahuan' => ['label' => 'Skill & Pengetahuan', 'icon' => 'fa-brain'],
                                    ];
                                @endphp

                                @foreach($kriteria as $field => $data)
                                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 focus-within:ring-2 focus-within:ring-teal-500 transition">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-2 flex items-center gap-2">
                                        <i class="fas {{ $data['icon'] }} text-teal-600 dark:text-teal-400"></i> {{ $data['label'] }}
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="{{ $field }}" min="0" max="100" required
                                            class="score-input w-full pl-4 pr-12 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring focus:ring-teal-500/20 transition font-bold font-mono text-lg shadow-xs [color-scheme:dark]"
                                            placeholder="0" value="{{ old($field, $application->$field ?? '') }}">
                                        <span class="absolute right-4 top-2.5 text-gray-400 dark:text-gray-500 text-xs font-bold font-mono">/ 100</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-8">
                            <h3 class="font-bold text-gray-800 dark:text-gray-100 text-lg border-b border-gray-100 dark:border-gray-700 pb-3 mb-4 flex items-center gap-2">
                                <i class="fas fa-comment-alt text-teal-600 dark:text-teal-400"></i> Catatan & Evaluasi Pembimbing
                            </h3>
                            <textarea name="catatan_pembimbing_lapangan" rows="4" 
                                class="w-full rounded-2xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-teal-500 focus:ring focus:ring-teal-500/20 transition shadow-xs text-xs sm:text-sm font-medium p-4"
                                placeholder="Tuliskan evaluasi, kesan pesan, serta saran perbaikan dan pengembangan diri untuk peserta magang...">{{ old('catatan_pembimbing_lapangan', $application->catatan_pembimbing_lapangan) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('pembimbing_lapangan.dashboard') }}" class="px-6 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400 font-bold hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-800 dark:hover:text-gray-100 transition text-xs">
                                Batal
                            </a>
                            <button type="submit" class="px-7 py-2.5 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-md transition flex items-center gap-2 active:scale-95 text-xs uppercase tracking-wider">
                                <i class="fas fa-check-circle"></i> Simpan & Selesaikan
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        function initGradeCalculator() {
            const inputs = document.querySelectorAll('.score-input');
            const avgDisplay = document.getElementById('avg-score');
            const gradeDisplay = document.getElementById('grade-label');

            if (!avgDisplay || !gradeDisplay) return;

            function calculateAverage() {
                let total = 0;
                let count = 0;

                inputs.forEach(input => {
                    const val = parseFloat(input.value);
                    if (!isNaN(val)) {
                        total += val;
                        count++;
                    }
                });

                if (count > 0) {
                    const avg = (total / inputs.length).toFixed(1);
                    avgDisplay.innerText = avg;
                    
                    if(avg >= 90) { gradeDisplay.innerText = 'Sangat Baik (A)'; gradeDisplay.className = 'inline-block mt-3 px-3.5 py-1 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 text-xs font-bold rounded-full'; }
                    else if(avg >= 80) { gradeDisplay.innerText = 'Baik (B)'; gradeDisplay.className = 'inline-block mt-3 px-3.5 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60 text-xs font-bold rounded-full'; }
                    else if(avg >= 70) { gradeDisplay.innerText = 'Cukup (C)'; gradeDisplay.className = 'inline-block mt-3 px-3.5 py-1 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60 text-xs font-bold rounded-full'; }
                    else { gradeDisplay.innerText = 'Kurang (D)'; gradeDisplay.className = 'inline-block mt-3 px-3.5 py-1 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60 text-xs font-bold rounded-full'; }
                } else {
                    avgDisplay.innerText = '0';
                    gradeDisplay.innerText = '-';
                }
            }

            calculateAverage();

            inputs.forEach(input => {
                input.addEventListener('input', calculateAverage);
            });
        }

        document.addEventListener('DOMContentLoaded', initGradeCalculator);
        document.addEventListener('turbo:load', initGradeCalculator);
    </script>
</x-app-layout>