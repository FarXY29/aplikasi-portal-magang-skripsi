                    <!-- Progress Card -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Progress Magang</h4>
                                <span class="p-2 bg-teal-50 rounded-xl text-teal-600"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                            <div class="text-2xl font-black text-gray-900 dark:text-gray-100 mb-1">
                                {{ $stats['elapsed_days'] }} <span class="text-sm font-normal text-gray-500 dark:text-gray-400">dari {{ $stats['total_days'] }} Hari</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Internship timeline completion rate.</p>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                                <span>Persentase</span>
                                <span>{{ $stats['progress_percent'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2">
                                <div class="bg-teal-600 h-2 rounded-full transition-all duration-500" style="width: {{ $stats['progress_percent'] }}%"></div>
                            </div>
                        </div>
                    </div>
