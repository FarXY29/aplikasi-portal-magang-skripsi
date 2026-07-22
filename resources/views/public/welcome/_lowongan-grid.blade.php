      <!-- Lowongan Pekerjaan Section -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow w-full">
          
          <!-- Global Announcement Banner -->
          @php
              $globalAnnouncement = \App\Models\Setting::where('key', 'announcement')->value('value');
          @endphp
          @if(!empty($globalAnnouncement))
              <div class="bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-transparent border border-amber-500/30 dark:border-amber-500/40 rounded-[2rem] p-5 sm:p-8 shadow-xs flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-10 overflow-hidden relative w-full">
                  <div class="absolute -right-6 -top-6 opacity-5 text-amber-600 pointer-events-none">
                      <i class="fas fa-bullhorn text-9xl"></i>
                  </div>
                  <div class="flex gap-4 items-start">
                      <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-amber-500/10">
                          <i class="fas fa-bullhorn text-lg"></i>
                      </div>
                      <div>
                          <span class="text-[9px] font-extrabold text-amber-800 dark:text-amber-300 bg-amber-500/20 px-2.5 py-1 rounded-lg border border-amber-500/30 uppercase tracking-widest">Pengumuman Penting</span>
                          <div class="text-slate-800 dark:text-slate-200 font-bold text-sm sm:text-base mt-2 leading-relaxed">
                              {!! nl2br(e($globalAnnouncement)) !!}
                          </div>
                      </div>
                  </div>
              </div>
          @endif

          <!-- Banner Penempatan Otomatis -->
          <div class="bg-gradient-to-r from-teal-900 via-teal-950 to-emerald-950 dark:from-teal-950 dark:via-gray-900 dark:to-emerald-950 rounded-[2.5rem] p-6 sm:p-10 text-white shadow-xl shadow-teal-950/35 mb-12 overflow-hidden relative border border-teal-800/40 w-full">
              <div class="absolute -right-8 -top-8 opacity-10 text-white pointer-events-none">
                  <i class="fas fa-route text-[10rem]"></i>
              </div>
              <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-stretch lg:items-center justify-between relative z-10 w-full">
                  <div class="flex gap-4 sm:gap-5 items-start">
                      <div class="w-12 h-12 rounded-2xl bg-teal-500/20 text-teal-300 flex items-center justify-center shrink-0 shadow-inner border border-teal-500/30">
                          <i class="fas fa-wand-magic-sparkles text-lg"></i>
                      </div>
                      <div>
                          <span class="text-[9px] font-extrabold text-emerald-300 uppercase tracking-widest bg-emerald-500/20 px-2.5 py-1 rounded-lg border border-emerald-500/30">Alokasi Cerdas</span>
                          <h3 class="text-white font-extrabold text-lg sm:text-2xl mt-2 leading-snug">Bingung Memilih Dinas / Instansi?</h3>
                          <p class="text-teal-100/80 text-xs sm:text-sm mt-1.5 max-w-2xl font-medium leading-relaxed">
                              Gunakan fitur <strong>Penempatan Otomatis</strong>! Sistem cerdas kami akan mencocokkan latar belakang jurusan Anda dengan instansi yang saat ini kuotanya masih tersedia secara berimbang.
                          </p>
                      </div>
                  </div>
                  <a href="{{ route('peserta.apply_automatic.form') }}" class="shrink-0 bg-white dark:bg-gray-800 text-teal-950 dark:text-teal-200 hover:bg-teal-50 dark:hover:bg-gray-700 px-6 py-4 rounded-2xl font-extrabold shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 active:scale-98 text-center text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                      <i class="fas fa-play text-xs"></i> Daftar Penempatan Otomatis
                  </a>
              </div>
          </div>

          <!-- Vacancies Section Header -->
           <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4 w-full">
               <div>
                   <span class="text-xs font-bold text-teal-600 dark:text-teal-400 tracking-widest uppercase bg-teal-50 dark:bg-teal-950/60 px-4 py-2 rounded-full border border-teal-200 dark:border-teal-800/60">Eksplorasi Peran</span>
                   <h2 id="lowongan" class="text-2xl sm:text-4xl font-extrabold text-slate-800 dark:text-gray-100 tracking-tight mt-4 scroll-mt-[95px]">Lowongan Magang Terbaru</h2>
                   <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm sm:text-base font-medium">Dapatkan kesempatan berharga untuk mengabdi dan belajar langsung di instansi pemerintahan.</p>
               </div>
               
               @if(request()->anyFilled(['posisi', 'instansi_id', 'jurusan', 'search']))
                   <a href="{{ route('home') }}#lowongan" class="group flex items-center bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60 px-5 py-3 rounded-2xl text-xs sm:text-sm font-bold hover:bg-rose-100 dark:hover:bg-rose-900/60 transition duration-300 self-start md:self-auto shadow-xs">
                       <i class="fas fa-undo-alt mr-2 group-hover:-rotate-180 transition-transform duration-500"></i> Bersihkan Filter
                   </a>
               @endif
           </div>

           <!-- Filter Dock Card -->
           <div class="bg-white dark:bg-gray-800 p-5 sm:p-8 rounded-[2.5rem] shadow-xs border border-slate-100 dark:border-gray-700 mb-8 w-full">
               <form action="{{ route('home') }}#lowongan" method="GET" id="filter-form" onsubmit="event.preventDefault(); let params = new URLSearchParams(new FormData(this)); for (let [k, v] of Array.from(params.entries())) { if (!v) params.delete(k); } window.location.href = '{{ route('home') }}?' + params.toString() + '#lowongan';" class="w-full">
                   @if(request('search'))
                       <input type="hidden" name="search" value="{{ request('search') }}">
                   @endif

                   <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 items-end w-full">
                       <!-- Select Instansi -->
                       <div class="lg:col-span-5 w-full">
                           <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase mb-2 ml-1.5 tracking-wider flex items-center gap-2">
                               <i class="fas fa-building text-teal-600 dark:text-teal-400"></i> Pilih Instansi / Dinas
                           </label>
                           <div class="relative w-full group">
                               <select name="instansi_id" class="w-full pl-5 pr-12 py-4 border border-slate-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base lg:text-sm font-bold bg-slate-50/50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-900 transition duration-300 appearance-none cursor-pointer text-slate-800 dark:text-gray-100 shadow-xs [color-scheme:dark]">
                                   <option value="" class="bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100">🏢 Semua Instansi Pemerintah</option>
                                   @foreach($instansis as $instansi)
                                       <option value="{{ $instansi->id }}" {{ request('instansi_id') == $instansi->id ? 'selected' : '' }} class="bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100">
                                           {{ Str::limit($instansi->nama_dinas, 45) }}
                                       </option>
                                   @endforeach
                               </select>
                               <span class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none text-slate-400 dark:text-gray-500 transition-colors group-hover:text-teal-600 dark:group-hover:text-teal-400">
                                   <i class="fas fa-chevron-down text-xs"></i>
                               </span>
                           </div>
                       </div>

                       <!-- Input Jurusan -->
                       <div class="lg:col-span-5 w-full">
                           <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase mb-2 ml-1.5 tracking-wider flex items-center gap-2">
                               <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400"></i> Cari Jurusan / Keahlian
                           </label>
                           <div class="relative w-full">
                               <input type="text" name="jurusan" id="jurusan-input" value="{{ request('jurusan') }}" placeholder="Contoh: Informatika, Akuntansi, SMK..." 
                                   class="w-full px-5 py-4 border border-slate-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base lg:text-sm font-bold bg-slate-50/50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-900 transition duration-300 text-slate-800 dark:text-gray-100 placeholder-slate-400 dark:placeholder-gray-500 shadow-xs">
                           </div>
                       </div>

                       <!-- Filter Button -->
                       <div class="lg:col-span-2 w-full">
                           <button type="submit" class="w-full bg-slate-900 dark:bg-teal-600 hover:bg-teal-600 dark:hover:bg-teal-500 text-white font-extrabold py-4 px-6 rounded-2xl shadow-md hover:shadow-lg active:scale-98 transition duration-300 flex items-center justify-center gap-2 text-xs uppercase tracking-wider">
                               <i class="fas fa-filter text-xs"></i> Terapkan
                           </button>
                       </div>
                   </div>
               </form>
           </div>

           <!-- Quick Filter Scrollable Pills -->
           <div class="flex items-center gap-3.5 overflow-x-auto pb-4 mb-8 no-scrollbar w-full max-w-full">
               <span class="text-xs font-extrabold text-slate-400 dark:text-gray-400 uppercase tracking-wider shrink-0 mr-1 flex items-center">
                   <i class="fas fa-bolt text-amber-500 mr-2"></i> Filter Cepat:
               </span>
               <a href="{{ route('home') }}#lowongan" class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition duration-300 {{ !request('jurusan') && !request('instansi_id') && !request('search') ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                   ✨ Semua Posisi
               </a>
               <a href="{{ route('home') }}?jurusan=Informatika#lowongan" class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition duration-300 {{ stripos(request('jurusan'), 'Informatika') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                   💻 Informatika & IT
               </a>
               <a href="{{ route('home') }}?jurusan=Akuntansi#lowongan" class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition duration-300 {{ stripos(request('jurusan'), 'Akuntansi') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                   📊 Akuntansi & Keuangan
               </a>
               <a href="{{ route('home') }}?jurusan=Administrasi#lowongan" class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition duration-300 {{ stripos(request('jurusan'), 'Administrasi') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                   🏛️ Administrasi
               </a>
               <a href="{{ route('home') }}?jurusan=SMK#lowongan" class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition duration-300 {{ stripos(request('jurusan'), 'SMK') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                   🏫 Khusus SMK
               </a>
               <a href="{{ route('home') }}?jurusan=SMA#lowongan" class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition duration-300 {{ stripos(request('jurusan'), 'SMA') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                   🎒 Khusus SMA
               </a>
               <a href="{{ route('home') }}?jurusan=S1#lowongan" class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition duration-300 {{ stripos(request('jurusan'), 'S1') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                   🎓 Mahasiswa (S1/D3)
               </a>
           </div>

          <!-- Vacancies Card Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 w-full">
              @forelse($lowongans as $loker)
                  @if($loker->kuota > 0)
                      <div x-data="{ showModal: false }" class="h-full flex flex-col w-full">
                          <!-- Job Card Wrapper -->
                          <div @click="showModal = true" class="cursor-pointer group bg-white dark:bg-gray-800 rounded-[2rem] border border-slate-200/80 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:border-teal-300 dark:hover:border-teal-500 hover:-translate-y-1 active:scale-[0.99] transition-all duration-300 flex flex-col h-full relative shadow-xs w-full">
                          
                              <!-- Card Header Bar -->
                              <div class="px-6 pt-6 pb-4 flex items-center justify-between gap-3 border-b border-slate-50 dark:border-gray-700/60 shrink-0">
                                  <div class="flex flex-wrap items-center gap-2">
                                      <span class="inline-flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60 text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-wider">
                                          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"></span>
                                          {{ $loker->status }}
                                      </span>
                                      @if($loker->kuota < 3)
                                          <span class="inline-flex items-center gap-1 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/60 text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-wider animate-pulse">
                                              🔥 Sisa {{ $loker->kuota }} Kursi
                                          </span>
                                      @else
                                          <span class="inline-flex items-center gap-1 bg-slate-100 dark:bg-gray-900 text-slate-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 text-[10px] px-2.5 py-1 rounded-lg font-bold">
                                              💺 Kuota: {{ $loker->kuota }}
                                          </span>
                                      @endif
                                  </div>
                                  <div class="text-slate-300 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors shrink-0">
                                      <i class="fas fa-chevron-right text-xs"></i>
                                  </div>
                              </div>

                              <!-- Job Title & Icon Info -->
                              <div class="p-6 pt-5 pb-4 flex items-start gap-4 shrink-0">
                                  <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center shadow-md shadow-teal-500/20 shrink-0 font-bold text-lg">
                                      <i class="fas fa-briefcase"></i>
                                  </div>
                                  <div class="min-w-0 flex-grow">
                                      <h3 class="text-base sm:text-lg font-black text-slate-800 dark:text-gray-100 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors duration-300 line-clamp-1 leading-snug" title="{{ $loker->judul_posisi }}">
                                          {{ $loker->judul_posisi }}
                                      </h3>
                                      <p class="text-xs text-slate-500 dark:text-gray-400 flex items-center font-bold mt-1.5">
                                          <i class="fas fa-building text-teal-600 dark:text-teal-400 mr-2 shrink-0"></i>
                                          <span class="truncate">{{ $loker->instansi->nama_dinas }}</span>
                                      </p>
                                  </div>
                              </div>

                              <!-- Major Requirement tags -->
                              <div class="px-6 py-2 flex-grow flex flex-col justify-start">
                                  <div class="flex flex-wrap items-center gap-2 mb-4">
                                      <span class="inline-flex items-center px-3 py-1 rounded-xl text-[11px] font-bold bg-slate-100 dark:bg-gray-900 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-700 max-w-full">
                                          <i class="fas fa-graduation-cap mr-2 text-teal-600 dark:text-teal-400 shrink-0"></i>
                                          <span class="truncate" title="{{ $loker->required_major }}">{{ $loker->required_major }}</span>
                                      </span>
                                      @if(preg_match('/SMA|SMK/i', $loker->required_major))
                                          <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-black bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60 shrink-0">
                                              🎒 SMA/SMK
                                          </span>
                                      @endif
                                  </div>

                                  <!-- Excerpt description -->
                                  <div class="prose prose-sm text-slate-500 dark:text-gray-400 text-xs sm:text-sm leading-relaxed line-clamp-2 mb-6 font-medium">
                                      {!! strip_tags($loker->deskripsi) !!}
                                  </div>
                              </div>

                              <!-- Action buttons footer -->
                              <div class="p-5 pt-3 bg-slate-50 dark:bg-gray-900 border-t border-slate-100 dark:border-gray-700/60 mt-auto shrink-0 flex items-center justify-between gap-3">
                                  @auth
                                      @if(auth()->user()->role == 'peserta')
                                          @php
                                              $userMajor = strtolower(trim(auth()->user()->major ?? ''));
                                              $reqMajor  = strtolower(trim($loker->required_major ?? ''));
                                              $isMatch = str_contains($reqMajor, 'semua jurusan') || 
                                                         str_contains($reqMajor, $userMajor) ||
                                                         $reqMajor == '' || 
                                                         $reqMajor == '-';
                                          @endphp

                                          @if($isMatch)
                                              <a @click.stop href="{{ route('peserta.daftar.form', $loker->id) }}" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-3.5 px-4 rounded-2xl font-bold shadow-md active:scale-98 transition text-center text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                                                  <span>Lamar Posisi Ini</span>
                                                  <i class="fas fa-arrow-right text-xs"></i>
                                              </a>
                                          @else
                                              <button disabled class="w-full bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 py-3.5 px-4 rounded-2xl font-bold cursor-not-allowed text-xs flex items-center justify-center gap-2 uppercase tracking-wider">
                                                  <i class="fas fa-lock text-xs"></i> Syarat Jurusan Tidak Sesuai
                                              </button>
                                          @endif
                                      @elseif(auth()->user()->role == 'admin_kota' || auth()->user()->role == 'admin_instansi')
                                          <button disabled class="w-full text-center text-xs font-bold text-gray-500 dark:text-gray-400 py-3 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl uppercase tracking-wider">Pratinjau Admin</button>
                                      @endif
                                  @else
                                      <a @click.stop href="{{ route('peserta.daftar.form', $loker->id) }}" class="w-full bg-slate-900 dark:bg-teal-600 hover:bg-teal-600 dark:hover:bg-teal-500 text-white py-3.5 px-4 rounded-2xl font-bold shadow-md active:scale-98 transition text-center text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                                          <span>Masuk & Lamar</span>
                                          <i class="fas fa-arrow-right text-xs"></i>
                                      </a>
                                  @endauth
                              </div>
                          </div>

                          <!-- Detail Loker Side Drawer / Bottom Sheet -->
                          <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex justify-end items-end sm:items-stretch" role="dialog" aria-modal="true">
                              <!-- Backdrop overlay -->
                              <div x-show="showModal" 
                                   x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                                   x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                                   class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" 
                                   @click="showModal = false"></div>

                              <!-- Drawer Panel -->
                              <div x-show="showModal" 
                                   x-transition:enter="transition ease-out duration-300 transform" 
                                   x-transition:enter-start="translate-y-full sm:translate-y-0 sm:translate-x-full" 
                                   x-transition:enter-end="translate-y-0 sm:translate-x-0" 
                                   x-transition:leave="transition ease-in duration-200 transform" 
                                   x-transition:leave-start="translate-y-0 sm:translate-x-0" 
                                   x-transition:leave-end="translate-y-full sm:translate-y-0 sm:translate-x-full"
                                   class="relative bg-white dark:bg-gray-800 rounded-t-[2.5rem] sm:rounded-t-none sm:rounded-l-[2.5rem] shadow-2xl w-full sm:max-w-md md:max-w-lg lg:max-w-xl xl:max-w-2xl h-[88vh] sm:h-full overflow-hidden flex flex-col z-10 transition-all text-left border-l border-slate-100 dark:border-gray-700">
                                  
                                  <!-- Mobile Drag handle bar -->
                                  <div class="sm:hidden w-12 h-1.5 bg-slate-200 dark:bg-gray-700 rounded-full mx-auto my-3.5 shrink-0"></div>

                                  <!-- Header Drawer -->
                                  <div class="px-6 sm:px-8 py-5 border-b border-slate-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 shrink-0 flex justify-between items-center">
                                      <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-gray-100 flex items-center gap-2.5">
                                          <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center border border-teal-200 dark:border-teal-800/60 shrink-0">
                                              <i class="fas fa-info-circle text-xs"></i>
                                          </div>
                                          Detail Lowongan Magang
                                      </h3>
                                      <button @click="showModal = false" class="text-slate-400 dark:text-gray-500 hover:text-slate-600 dark:hover:text-gray-300 bg-gray-100 dark:bg-gray-800 p-2 rounded-full transition"><i class="fas fa-times"></i></button>
                                  </div>
                                  
                                  <!-- Body Drawer Content -->
                                  <div class="px-6 sm:px-8 pt-6 pb-8 overflow-y-auto flex-grow space-y-6">
                                      
                                      <!-- Branding & Instansi Block -->
                                      @php
                                          $cleanDinas = trim(str_ireplace(['dinas', 'badan', 'kantor', 'bagian', 'sekretariat'], '', $loker->instansi->nama_dinas));
                                          $initials = strtoupper(substr($cleanDinas, 0, 2));
                                      @endphp
                                      <div class="flex flex-col sm:flex-row items-start gap-4 pb-2">
                                          <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-teal-500/20 shrink-0">
                                              {{ $initials }}
                                          </div>
                                          <div class="space-y-1">
                                              <h4 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-gray-100 leading-tight">
                                                  {{ $loker->judul_posisi }}
                                              </h4>
                                              <p class="text-xs sm:text-sm font-bold text-teal-600 dark:text-teal-400">
                                                  {{ $loker->instansi->nama_dinas }}
                                              </p>
                                              @if(!empty($loker->instansi->alamat))
                                                  <p class="text-xs text-slate-500 dark:text-gray-400 flex items-start gap-2 pt-1 leading-relaxed font-medium">
                                                      <i class="fas fa-map-marker-alt text-rose-500 shrink-0 mt-0.5 animate-bounce"></i>
                                                      <span>{{ $loker->instansi->alamat }}</span>
                                                  </p>
                                              @endif
                                          </div>
                                      </div>

                                      <!-- Quick Info Grid Cards -->
                                      <div class="grid grid-cols-2 gap-3">
                                          <!-- Status Card -->
                                          <div class="bg-slate-50 dark:bg-gray-900 border border-slate-100 dark:border-gray-700 rounded-2xl p-3.5 flex flex-col justify-between">
                                              <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Status Lowongan</span>
                                              <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-1 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-lg border border-emerald-200 dark:border-emerald-800/60 w-fit">
                                                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"></span>
                                                  {{ $loker->status }}
                                              </span>
                                          </div>

                                          <!-- Kuota Card -->
                                          <div class="bg-slate-50 dark:bg-gray-900 border border-slate-100 dark:border-gray-700 rounded-2xl p-3.5 flex flex-col justify-between">
                                              <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Kapasitas Kursi</span>
                                              <span class="text-xs font-bold text-slate-800 dark:text-gray-100 mt-1 flex items-center gap-1.5">
                                                  <i class="fas fa-users text-teal-600 dark:text-teal-400 text-[10px]"></i>
                                                  <span>{{ $loker->kuota }} Posisi Tersedia</span>
                                              </span>
                                          </div>

                                          <!-- Deadline Card -->
                                          <div class="bg-slate-50 dark:bg-gray-900 border border-slate-100 dark:border-gray-700 rounded-2xl p-3.5 flex flex-col justify-between">
                                              <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Batas Pendaftaran</span>
                                              <span class="text-xs font-bold text-slate-800 dark:text-gray-100 mt-1 flex items-center gap-1.5">
                                                  <i class="fas fa-calendar-alt text-teal-600 dark:text-teal-400 text-[10px]"></i>
                                                  <span>{{ \Carbon\Carbon::parse($loker->batas_daftar)->translatedFormat('d F Y') }}</span>
                                              </span>
                                          </div>

                                          <!-- Kualifikasi Card -->
                                          <div class="bg-slate-50 dark:bg-gray-900 border border-slate-100 dark:border-gray-700 rounded-2xl p-3.5 flex flex-col justify-between">
                                              <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Kualifikasi Utama</span>
                                              <span class="text-xs font-bold text-slate-800 dark:text-gray-100 mt-1 truncate flex items-center gap-1.5" title="{{ $loker->required_major }}">
                                                  <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400 text-[10px]"></i>
                                                  <span class="truncate">{{ $loker->required_major }}</span>
                                              </span>
                                          </div>
                                      </div>

                                      <!-- Detail Job Description -->
                                      <div class="space-y-3">
                                          <h5 class="text-xs font-bold text-slate-800 dark:text-gray-100 uppercase tracking-wider flex items-center gap-2">
                                              <i class="fas fa-file-alt text-teal-600 dark:text-teal-400"></i> Deskripsi Pekerjaan & Persyaratan
                                          </h5>
                                          <div class="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-gray-300 bg-slate-50 dark:bg-gray-900 p-5 rounded-2xl border border-slate-100 dark:border-gray-700 text-xs sm:text-sm font-medium leading-relaxed">
                                              {!! $loker->deskripsi !!}
                                          </div>
                                      </div>

                                      <!-- Detailed Office & Penanggung Jawab Section -->
                                      <div class="space-y-3">
                                          <h5 class="text-xs font-bold text-slate-800 dark:text-gray-100 uppercase tracking-wider flex items-center gap-2">
                                              <i class="fas fa-building text-teal-600 dark:text-teal-400"></i> Informasi Kantor & Penempatan
                                          </h5>
                                          
                                          <div class="bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-2xl p-4 sm:p-5 space-y-3.5 text-xs sm:text-sm">
                                              @if(!empty($loker->instansi->nama_pejabat))
                                                  <div class="flex items-start gap-3">
                                                      <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 mt-0.5 border border-teal-200 dark:border-teal-800/60">
                                                          <i class="fas fa-user-tie text-xs"></i>
                                                      </div>
                                                      <div>
                                                          <span class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider">Pejabat Penanggung Jawab</span>
                                                          <span class="font-bold text-slate-800 dark:text-gray-200">{{ $loker->instansi->nama_pejabat }}</span>
                                                          <span class="block text-[11px] text-slate-500 dark:text-gray-400 mt-0.5">{{ $loker->instansi->jabatan_pejabat }} (NIP: {{ $loker->instansi->nip_pejabat }})</span>
                                                      </div>
                                                  </div>
                                              @endif

                                              @if(!empty($loker->instansi->jam_mulai_masuk) && !empty($loker->instansi->jam_mulai_pulang))
                                                  <div class="flex items-start gap-3 border-t border-slate-100 dark:border-gray-800 pt-3">
                                                      <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 mt-0.5 border border-teal-200 dark:border-teal-800/60">
                                                          <i class="fas fa-clock text-xs"></i>
                                                      </div>
                                                      <div>
                                                          <span class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider">Jam Absensi Kerja Dinas</span>
                                                          <span class="font-bold text-slate-800 dark:text-gray-200 font-mono">{{ substr($loker->instansi->jam_mulai_masuk, 0, 5) }} s/d {{ substr($loker->instansi->jam_mulai_pulang, 0, 5) }} WITA</span>
                                                          <span class="block text-[10px] text-slate-400 dark:text-gray-500 mt-0.5 font-medium">Wajib absen masuk dan pulang tepat waktu sesuai radius jangkauan dinas.</span>
                                                      </div>
                                                  </div>
                                              @endif

                                              @if(!empty($loker->instansi->latitude) && !empty($loker->instansi->longitude))
                                                  <div class="flex items-start gap-3 border-t border-slate-100 dark:border-gray-800 pt-3">
                                                      <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 mt-0.5 border border-teal-200 dark:border-teal-800/60">
                                                          <i class="fas fa-map-marked-alt text-xs"></i>
                                                      </div>
                                                      <div class="flex-grow">
                                                          <span class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider">Titik Koordinat Absensi</span>
                                                          <span class="text-slate-800 dark:text-gray-200 block font-bold text-xs mt-0.5">Radius: {{ $loker->instansi->radius_absen ?? '100' }} meter dari kantor</span>
                                                          <a href="https://www.google.com/maps/search/?api=1&query={{ $loker->instansi->latitude }},{{ $loker->instansi->longitude }}" target="_blank" class="inline-flex items-center gap-1.5 text-teal-600 dark:text-teal-400 font-bold hover:underline mt-2 text-xs">
                                                              <span>Buka Google Maps</span>
                                                              <i class="fas fa-external-link-alt text-[10px]"></i>
                                                          </a>
                                                      </div>
                                                  </div>
                                              @endif
                                          </div>
                                      </div>
                                  </div>

                                  <!-- Footer Sticky Drawer -->
                                  <div class="px-6 sm:px-8 py-5 border-t border-slate-100 dark:border-gray-700 bg-white dark:bg-gray-800 flex items-center justify-end gap-3 shrink-0 z-20">
                                      <button @click="showModal = false" class="px-5 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-slate-700 dark:text-gray-200 rounded-xl font-bold transition text-xs uppercase tracking-wider">
                                          Tutup
                                      </button>
                                      
                                      @auth
                                          @if(auth()->user()->role == 'peserta' && ($isMatch ?? true))
                                              <a href="{{ route('peserta.daftar.form', $loker->id) }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl font-bold shadow-md transition text-xs uppercase tracking-wider flex items-center gap-2">
                                                  Ajukan Lamaran <i class="fas fa-arrow-right text-xs"></i>
                                              </a>
                                          @endif
                                      @else
                                          <a href="{{ route('peserta.daftar.form', $loker->id) }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl font-bold shadow-md transition text-xs uppercase tracking-wider flex items-center gap-2">
                                              Masuk & Lamar <i class="fas fa-arrow-right text-xs"></i>
                                          </a>
                                      @endauth
                                  </div>
                              </div>
                          </div>
                      </div>
                  @endif
              @empty
                  <!-- Empty State Lowongan -->
                  <div class="col-span-full py-16 sm:py-24 text-center">
                      <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-200 dark:border-gray-700">
                          <i class="far fa-folder-open text-3xl text-gray-400 dark:text-gray-500"></i>
                      </div>
                      <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-gray-100">Tidak Ada Lowongan Ditemukan</h3>
                      <p class="text-slate-500 dark:text-gray-400 mt-1 max-w-sm mx-auto text-xs sm:text-sm font-medium">Kami tidak menemukan lowongan yang sesuai dengan kriteria filter Anda. Silakan bersihkan pencarian atau ganti pilihan instansi.</p>
                      <a href="{{ route('home') }}#lowongan" class="inline-flex items-center gap-2 mt-5 bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition shadow-md uppercase tracking-wider">
                          <i class="fas fa-undo text-xs"></i> Reset Pencarian
                      </a>
                  </div>
              @endforelse
          </div>
          
          <!-- Laravel Pagination Links -->
          <div class="mt-12 sm:mt-16 w-full" id="lowongan-pagination">
              {{ $lowongans->links() }}
          </div>

          <!-- Script Pagination & Scroll Helper -->
          <script>
              function scrollToLowonganHeader() {
                  const targetEl = document.getElementById('lowongan');
                  if (targetEl) {
                      const absoluteTop = targetEl.getBoundingClientRect().top + window.pageYOffset;
                      const finalY = Math.max(0, absoluteTop - 95);
                      window.scrollTo({ top: finalY, behavior: 'instant' });
                  }
              }

              if (window.location.hash === '#lowongan') {
                  scrollToLowonganHeader();
              }

              document.addEventListener('DOMContentLoaded', function() {
                  const paginationLinks = document.querySelectorAll('#lowongan-pagination a');
                  paginationLinks.forEach(link => {
                      const url = new URL(link.href, window.location.origin);
                      url.hash = 'lowongan';
                      link.href = url.toString();
                  });

                  if (window.location.hash === '#lowongan') {
                      scrollToLowonganHeader();
                      setTimeout(() => {
                          scrollToLowonganHeader();
                          document.documentElement.style.scrollBehavior = 'smooth';
                      }, 30);
                  }
              });
          </script>
      </div>
