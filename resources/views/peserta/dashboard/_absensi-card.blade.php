                    <!-- Attendance Stats Card -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Statistik Absensi</h4>
                                <span class="p-2 bg-purple-50 dark:bg-purple-950/30 rounded-xl text-purple-600 dark:text-purple-400"><i class="fas fa-calendar-check"></i></span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div class="bg-green-50/50 dark:bg-green-950/20 p-3 rounded-xl border border-green-100 dark:border-green-900/50">
                                    <span class="block text-xl font-bold text-green-700 dark:text-green-400">{{ $stats['attendance']['hadir'] }}</span>
                                    <span class="text-[10px] font-bold text-green-600 dark:text-green-500 uppercase">Hadir</span>
                                </div>
                                <div class="bg-yellow-50/50 dark:bg-yellow-950/20 p-3 rounded-xl border border-yellow-100 dark:border-yellow-900/50">
                                    <span class="block text-xl font-bold text-yellow-700 dark:text-yellow-400">{{ $stats['attendance']['izin'] }}</span>
                                    <span class="text-[10px] font-bold text-yellow-600 dark:text-yellow-500 uppercase">Izin</span>
                                </div>
                                <div class="bg-red-50/50 dark:bg-red-950/20 p-3 rounded-xl border border-red-100 dark:border-red-900/50">
                                    <span class="block text-xl font-bold text-red-700 dark:text-red-400">{{ $stats['attendance']['alpa'] }}</span>
                                    <span class="text-[10px] font-bold text-red-600 dark:text-red-500 uppercase">Alpa</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-3 text-center">Data absensi kumulatif selama masa magang.</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 text-center">
                            <a href="{{ route('peserta.absensi.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-600 hover:text-teal-800 dark:text-teal-400 dark:hover:text-teal-300 transition">
                                <i class="fas fa-history"></i> Lihat Riwayat Absen
                            </a>
                        </div>
                    </div>
