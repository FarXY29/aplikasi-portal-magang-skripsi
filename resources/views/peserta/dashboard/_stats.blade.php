<!-- Progress Card -->
                    <div class="stat-summary-card stagger-1 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Progress Magang</h4>
                                <span class="p-2 bg-teal-50 dark:bg-teal-950/30 rounded-xl text-teal-600 dark:text-teal-400"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                            <div class="text-3xl font-black text-gray-900 dark:text-gray-100 mb-1 animate-count-up">
                                {{ $stats['elapsed_days'] }} <span class="text-sm font-normal text-gray-500 dark:text-gray-400">dari {{ $stats['total_days'] }} Hari</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Persentase penyelesaian periode magang Anda.</p>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold text-gray-700 dark:text-gray-300 mb-2">
                                <span>Penyelesaian</span>
                                <span class="text-teal-600 dark:text-teal-400">{{ $stats['progress_percent'] }}%</span>
                            </div>
                            <div class="progress-bar-track">
                                <div class="progress-bar-fill" style="--progress-width: {{ $stats['progress_percent'] }}%"></div>
                            </div>
                        </div>
                    </div>
