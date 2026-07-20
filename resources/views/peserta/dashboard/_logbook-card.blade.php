                    <!-- Logbook & Pembimbing Lapangan Card -->
                    <div class="stat-summary-card stagger-3 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Logbook Harian</h4>
                                <a href="{{ route('peserta.logbook.index') }}" class="p-2 bg-blue-50 dark:bg-blue-950/30 rounded-xl text-blue-600 dark:text-blue-400 hover:bg-blue-100 transition" title="Buka Logbook">
                                    <i class="fas fa-book-open"></i>
                                </a>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-gray-50 dark:bg-gray-900 p-3.5 rounded-xl border border-gray-200 dark:border-gray-700 text-center">
                                    <span class="block text-2xl font-black text-gray-800 dark:text-gray-200 animate-count-up">{{ $stats['logs_count'] }}</span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Total Entri</span>
                                </div>
                                <div class="bg-emerald-50 dark:bg-emerald-950/20 p-3.5 rounded-xl border border-emerald-100 dark:border-emerald-900/50 text-center">
                                    <span class="block text-2xl font-black text-emerald-700 dark:text-emerald-400 animate-count-up">{{ $stats['logs_validated'] }}</span>
                                    <span class="text-[10px] font-bold text-emerald-600 uppercase">Divalidasi</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pembimbing Lapangan -->
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-3 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-teal-50 dark:bg-teal-950/30 flex items-center justify-center text-teal-600 dark:text-teal-400 font-bold border border-teal-100 dark:border-teal-900/50 text-xs flex-shrink-0">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="block text-[10px] font-bold text-gray-400 uppercase leading-none">Pembimbing Lapangan</span>
                                <span class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate block">{{ $activeApp->pembimbing_lapangan->name ?? 'Belum Ditunjuk' }}</span>
                            </div>
                            <a href="{{ route('peserta.logbook.index') }}" class="text-xs font-bold text-teal-600 hover:text-teal-800 dark:text-teal-400 shrink-0">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
