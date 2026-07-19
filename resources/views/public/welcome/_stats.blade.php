<!-- Floating Bento Statistics Cards -->
     <section class="relative z-20 -mt-10 sm:-mt-16 px-4 w-full">
         <div class="max-w-5xl mx-auto w-full">
             <div class="grid grid-cols-3 gap-3.5 sm:gap-6 w-full">
                  <!-- Instansi Card -->
                  <div class="bg-white dark:bg-gray-800/95 backdrop-blur-md p-4 sm:p-7 rounded-[2rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100/80 dark:border-gray-700/50 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-3.5 sm:gap-5 transform hover:-translate-y-1.5 transition-all duration-300 group">
                      <div class="w-11 h-11 sm:w-14 sm:h-14 bg-teal-50 dark:bg-teal-950/40 rounded-2xl flex items-center justify-center text-teal-600 dark:text-teal-400 group-hover:bg-teal-600 group-hover:text-white dark:group-hover:bg-teal-600 dark:group-hover:text-white transition-all duration-500 shadow-inner shrink-0">
                          <i class="fas fa-building text-base sm:text-xl"></i>
                      </div>
                      <div class="min-w-0">
                          <div class="text-xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tight truncate leading-none sm:leading-tight">{{ $totalInstansi }}</div>
                          <div class="text-slate-400 dark:text-slate-500 text-[10px] sm:text-xs font-bold uppercase tracking-wider mt-1.5 truncate">Instansi</div>
                      </div>
                  </div>
                  <!-- Lowongan Card -->
                  <div class="bg-white dark:bg-gray-800/95 backdrop-blur-md p-4 sm:p-7 rounded-[2rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100/80 dark:border-gray-700/50 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-3.5 sm:gap-5 transform hover:-translate-y-1.5 transition-all duration-300 group">
                      <div class="w-11 h-11 sm:w-14 sm:h-14 bg-emerald-50 dark:bg-emerald-950/40 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-600 group-hover:text-white dark:group-hover:bg-emerald-600 dark:group-hover:text-white transition-all duration-500 shadow-inner shrink-0">
                          <i class="fas fa-briefcase text-base sm:text-xl"></i>
                      </div>
                      <div class="min-w-0">
                          <div class="text-xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tight truncate leading-none sm:leading-tight">{{ $totalLowongan }}</div>
                          <div class="text-slate-400 dark:text-slate-500 text-[10px] sm:text-xs font-bold uppercase tracking-wider mt-1.5 truncate">Posisi Aktif</div>
                      </div>
                  </div>
                  <!-- Alumni Card -->
                  <div class="bg-white dark:bg-gray-800/95 backdrop-blur-md p-4 sm:p-7 rounded-[2rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100/80 dark:border-gray-700/50 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-3.5 sm:gap-5 transform hover:-translate-y-1.5 transition-all duration-300 group">
                      <div class="w-11 h-11 sm:w-14 sm:h-14 bg-amber-50 dark:bg-amber-950/40 rounded-2xl flex items-center justify-center text-amber-500 dark:text-amber-400 group-hover:bg-amber-500 group-hover:text-white dark:group-hover:bg-amber-500 dark:group-hover:text-white transition-all duration-500 shadow-inner shrink-0">
                          <i class="fas fa-user-graduate text-base sm:text-xl"></i>
                      </div>
                      <div class="min-w-0">
                          <div class="text-xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tight truncate leading-none sm:leading-tight">{{ $totalAlumni }}</div>
                          <div class="text-slate-400 dark:text-slate-500 text-[10px] sm:text-xs font-bold uppercase tracking-wider mt-1.5 truncate">Alumni Magang</div>
                      </div>
                  </div>
             </div>
         </div>
     </section>
