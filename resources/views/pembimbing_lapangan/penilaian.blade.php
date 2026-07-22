<x-app-layout>
    @push('head')
        <meta name="turbo-cache-control" content="no-cache">
    @endpush
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <i class="fas fa-clipboard-check text-teal-600 dark:text-teal-400"></i>
                {{ __('Formulir Penilaian Akhir') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 print:hidden">
                <a href="{{ route('pembimbing_lapangan.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center mr-2 group-hover:border-teal-500 dark:group-hover:border-teal-400 shadow-sm">
                        <i class="fas fa-arrow-left text-xs text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <div class="w-full lg:w-1/3 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 lg:sticky lg:top-8">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center text-white font-bold text-3xl mx-auto shadow-md mb-3 border-4 border-teal-50 dark:border-teal-900/50">
                            {{ strtoupper(substr($application->user->name, 0, 1)) }}
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-gray-100 text-lg">{{ $application->user->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $application->user->email }}</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Periode</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200 text-sm">
                                {{ \Carbon\Carbon::parse($application->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($application->tanggal_selesai)->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 text-center">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-2">Estimasi Nilai Akhir</p>
                        <h2 class="text-5xl font-black text-teal-600 dark:text-teal-400" id="avg-score">0</h2>
                        <span class="inline-block mt-2 px-3 py-1 bg-teal-100 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200 dark:border-teal-800 text-xs font-bold rounded-full" id="grade-label">-</span>
                    </div>
                </div>

                <div class="w-full lg:w-2/3 bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <form action="{{ route('pembimbing_lapangan.simpan_nilai', $application->id) }}" method="POST" id="gradingForm">
                        @csrf
                        
                        <div class="mb-8">
                            <h3 class="font-bold text-gray-800 dark:text-gray-200 text-lg border-b border-gray-100 dark:border-gray-700 pb-2 mb-4 flex items-center gap-2">
                                <i class="fas fa-star text-yellow-400"></i> Kriteria Penilaian
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6 bg-blue-50 dark:bg-blue-950/30 p-3.5 rounded-xl border border-blue-100 dark:border-blue-900/40">
                                <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mr-1"></i>
                                Isi nilai dengan skala <strong>0 - 100</strong>. Pastikan penilaian objektif berdasarkan kinerja peserta.
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                @php
                                    $kriteria = [
                                        'nilai_kerajinan' => ['label' => 'Kerajinan', 'icon' => 'fa-briefcase'],
                                        'nilai_disiplin' => ['label' => 'Disiplin', 'icon' => 'fa-clock'],
                                        'nilai_adaptasi' => ['label' => 'Adaptasi', 'icon' => 'fa-sync-alt'],
                                        'nilai_kreatifitas' => ['label' => 'Kreatifitas', 'icon' => 'fa-lightbulb'],
                                        'nilai_skill_pengetahuan' => ['label' => 'Skill dan Pengetahuan', 'icon' => 'fa-brain'],
                                    ];
                                @endphp

                                @foreach($kriteria as $field => $data)
                                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-xl border border-gray-200 dark:border-gray-700 focus-within:ring-2 focus-within:ring-teal-500 transition">
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2 flex items-center gap-2">
                                        <i class="fas {{ $data['icon'] }} text-teal-500 dark:text-teal-400"></i> {{ $data['label'] }}
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="{{ $field }}" min="0" max="100" required
                                            class="score-input w-full pl-4 pr-12 py-2 rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:border-teal-500 focus:ring focus:ring-teal-500/20 transition font-bold text-lg"
                                            placeholder="0" value="{{ old($field, $application->$field ?? '') }}">
                                        <span class="absolute right-4 top-2.5 text-gray-400 dark:text-gray-500 text-sm font-bold">/100</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-8">
                            <h3 class="font-bold text-gray-800 dark:text-gray-200 text-lg border-b border-gray-100 dark:border-gray-700 pb-2 mb-4 flex items-center gap-2">
                                <i class="fas fa-comment-alt text-teal-500 dark:text-teal-400"></i> Catatan & Saran untuk Peserta
                            </h3>
                            <textarea name="catatan_pembimbing_lapangan" rows="4" 
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-teal-500 focus:ring focus:ring-teal-500/20 transition shadow-sm text-sm"
                                placeholder="Tuliskan evaluasi, kesan pesan, serta saran perbaikan dan pengembangan diri untuk peserta magang...">{{ old('catatan_pembimbing_lapangan', $application->catatan_pembimbing_lapangan) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('pembimbing_lapangan.dashboard') }}" class="px-6 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-800 dark:hover:text-gray-100 transition text-sm">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-3 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-lg shadow-teal-500/20 transition transform active:scale-95 text-sm flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan & Selesaikan
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('turbo:load', function() {
            const inputs = document.querySelectorAll('.score-input');
            const avgDisplay = document.getElementById('avg-score');
            const gradeDisplay = document.getElementById('grade-label');

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
                    
                    if(avg >= 90) { gradeDisplay.innerText = 'Sangat Baik (A)'; gradeDisplay.className = 'inline-block mt-2 px-3 py-1 bg-green-100 dark:bg-green-950/60 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800 text-xs font-bold rounded-full'; }
                    else if(avg >= 80) { gradeDisplay.innerText = 'Baik (B)'; gradeDisplay.className = 'inline-block mt-2 px-3 py-1 bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800 text-xs font-bold rounded-full'; }
                    else if(avg >= 70) { gradeDisplay.innerText = 'Cukup (C)'; gradeDisplay.className = 'inline-block mt-2 px-3 py-1 bg-yellow-100 dark:bg-yellow-950/60 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800 text-xs font-bold rounded-full'; }
                    else { gradeDisplay.innerText = 'Kurang (D)'; gradeDisplay.className = 'inline-block mt-2 px-3 py-1 bg-red-100 dark:bg-red-950/60 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800 text-xs font-bold rounded-full'; }
                } else {
                    avgDisplay.innerText = '0';
                    gradeDisplay.innerText = '-';
                }
            }

            calculateAverage();

            inputs.forEach(input => {
                input.addEventListener('input', calculateAverage);
            });
        });
    </script>
</x-app-layout>