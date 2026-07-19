                    <!-- Logbook & Pembimbing Lapangan Card -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Logbook Harian</h4>
                                <span class="p-2 bg-blue-50 rounded-xl text-blue-600"><i class="fas fa-book"></i></span>
                            </div>
                            <div class="flex justify-between items-center gap-4 bg-gray-50 dark:bg-gray-900 p-3 rounded-xl border border-gray-150 mb-3">
                                <div>
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Total Log</span>
                                    <span class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $stats['logs_count'] }} Entri</span>
                                </div>
                                <div class="w-px h-8 bg-gray-200"></div>
                                <div>
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Valid</span>
                                    <span class="text-lg font-bold text-green-600">{{ $stats['logs_validated'] }} Entri</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pembimbing Lapangan Contact Details -->
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-3 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 font-bold border border-teal-100 text-xs">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-[10px] font-bold text-gray-400 uppercase leading-none">Pembimbing Lapangan</span>
                                <span class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate block">{{ $activeApp->pembimbing_lapangan->name ?? 'Belum Ditunjuk' }}</span>
                            </div>
                        </div>
                    </div>
