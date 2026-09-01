      <!-- Lowongan Pekerjaan Section -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow w-full" 
           x-data="lowonganGridManager()" 
           x-init="initGrid()"
           id="lowongan-explorer-container">
          
          <!-- Global Announcement Banner -->
          @php
              $globalAnnouncement = \App\Models\Setting::where('key', 'announcement')->value('value');
          @endphp
          @if(!empty($globalAnnouncement))
              <div class="reveal bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-transparent border border-amber-500/30 dark:border-amber-500/40 rounded-[2rem] p-5 sm:p-8 shadow-xs flex flex-col md:flex-row gap-5 items-start md:items-center justify-between mb-10 overflow-hidden relative w-full" style="--reveal-delay: 0ms" x-intersect.once="$el.classList.add('revealed')">
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
          <div class="reveal bg-gradient-to-r from-teal-900 via-teal-950 to-emerald-950 dark:from-teal-950 dark:via-gray-900 dark:to-emerald-950 rounded-[2.5rem] p-6 sm:p-10 text-white shadow-xl shadow-teal-950/35 mb-12 overflow-hidden relative border border-teal-800/40 w-full" style="--reveal-delay: 100ms" x-intersect.once="$el.classList.add('revealed')">
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

          <!-- Vacancies Section Header & Results Count -->
          <div class="reveal flex flex-col md:flex-row justify-between items-start md:items-end mb-6 gap-4 w-full" style="--reveal-delay: 0ms" x-intersect.once="$el.classList.add('revealed')">
              <div>
                  <span class="text-xs font-bold text-teal-600 dark:text-teal-400 tracking-widest uppercase bg-teal-50 dark:bg-teal-950/60 px-4 py-2 rounded-full border border-teal-200 dark:border-teal-800/60">Eksplorasi Peran</span>
                  <h2 id="lowongan" class="text-2xl sm:text-4xl font-extrabold text-slate-800 dark:text-gray-100 tracking-tight mt-4 scroll-mt-[95px]">Lowongan Magang Tersedia</h2>
                  <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm sm:text-base font-medium">Dapatkan kesempatan berharga untuk mengabdi dan belajar langsung di instansi pemerintahan.</p>
              </div>
              
              <div class="flex items-center gap-3">
                  <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-teal-50/80 dark:bg-teal-950/40 border border-teal-200/70 dark:border-teal-800/50 text-teal-700 dark:text-teal-300 text-xs font-bold shadow-2xs">
                      <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                      <span>Ditemukan <strong>{{ $lowongans->total() }}</strong> Posisi</span>
                  </div>

                  @if(request()->anyFilled(['posisi', 'instansi_id', 'jurusan', 'major_category_id', 'search', 'sort']))
                      <a href="{{ route('home') }}#lowongan" class="group flex items-center bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60 px-4 py-2.5 rounded-2xl text-xs font-bold hover:bg-rose-100 dark:hover:bg-rose-900/60 transition duration-300 shadow-2xs">
                          <i class="fas fa-undo-alt mr-1.5 group-hover:-rotate-180 transition-transform duration-500 text-xs"></i> Reset
                      </a>
                  @endif
              </div>
          </div>

          <!-- Active Filter Chips Bar (Touch-Swipeable on Mobile) -->
          @php
              $activeInstansi = request('instansi_id') ? $instansis->firstWhere('id', request('instansi_id')) : null;
              $activeCategory = request('major_category_id') && isset($majorCategories) ? $majorCategories->firstWhere('id', request('major_category_id')) : null;
          @endphp

          @if(request()->anyFilled(['search', 'instansi_id', 'major_category_id', 'jurusan', 'sort']))
              <div class="reveal mb-5 p-3.5 sm:p-4 rounded-2xl bg-slate-50 dark:bg-gray-800/60 border border-slate-200/70 dark:border-gray-700/60 flex items-center gap-2 text-xs overflow-x-auto no-scrollbar scroll-smooth flex-nowrap w-full -mx-4 px-4 sm:mx-0 sm:px-4" style="--reveal-delay: 50ms" x-intersect.once="$el.classList.add('revealed')">
                  <span class="font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider text-[10px] flex items-center gap-1.5 shrink-0 mr-1">
                      <i class="fas fa-filter text-teal-600 dark:text-teal-400"></i> Filter Aktif:
                  </span>

                  @if(request('search'))
                      @php
                          $querySearch = request()->except('search');
                      @endphp
                      <a href="{{ route('home', $querySearch) }}#lowongan" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-slate-200 font-bold hover:border-rose-400 hover:text-rose-600 transition shadow-2xs group">
                          <span>Kata Kunci: <em>"{{ request('search') }}"</em></span>
                          <i class="fas fa-times text-[10px] text-slate-400 group-hover:text-rose-500 transition"></i>
                      </a>
                  @endif

                  @if($activeInstansi)
                      @php
                          $queryInstansi = request()->except('instansi_id');
                      @endphp
                      <a href="{{ route('home', $queryInstansi) }}#lowongan" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-slate-200 font-bold hover:border-rose-400 hover:text-rose-600 transition shadow-2xs group">
                          <i class="fas fa-building text-teal-600 dark:text-teal-400 text-xs"></i>
                          <span>{{ Str::limit($activeInstansi->nama_dinas, 24) }}</span>
                          <i class="fas fa-times text-[10px] text-slate-400 group-hover:text-rose-500 transition"></i>
                      </a>
                  @endif

                  @if($activeCategory)
                      @php
                          $queryCategory = request()->except('major_category_id');
                      @endphp
                      <a href="{{ route('home', $queryCategory) }}#lowongan" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-slate-200 font-bold hover:border-rose-400 hover:text-rose-600 transition shadow-2xs group">
                          <i class="fas fa-layer-group text-teal-600 dark:text-teal-400 text-xs"></i>
                          <span>{{ $activeCategory->name }}</span>
                          <i class="fas fa-times text-[10px] text-slate-400 group-hover:text-rose-500 transition"></i>
                      </a>
                  @endif

                  @if(request('jurusan'))
                      @php
                          $queryJurusan = request()->except('jurusan');
                      @endphp
                      <a href="{{ route('home', $queryJurusan) }}#lowongan" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-slate-200 font-bold hover:border-rose-400 hover:text-rose-600 transition shadow-2xs group">
                          <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400 text-xs"></i>
                          <span>Jurusan: "{{ request('jurusan') }}"</span>
                          <i class="fas fa-times text-[10px] text-slate-400 group-hover:text-rose-500 transition"></i>
                      </a>
                  @endif

                  @if(request('sort') && request('sort') !== 'latest')
                      @php
                          $querySort = request()->except('sort');
                          $sortLabel = request('sort') === 'deadline_asc' ? 'Batas Waktu' : 'Kuota';
                      @endphp
                      <a href="{{ route('home', $querySort) }}#lowongan" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-slate-200 font-bold hover:border-rose-400 hover:text-rose-600 transition shadow-2xs group">
                          <i class="fas fa-arrow-down-short-wide text-teal-600 dark:text-teal-400 text-xs"></i>
                          <span>Urut: {{ $sortLabel }}</span>
                          <i class="fas fa-times text-[10px] text-slate-400 group-hover:text-rose-500 transition"></i>
                      </a>
                  @endif

                  <a href="{{ route('home') }}#lowongan" class="shrink-0 text-rose-600 dark:text-rose-400 hover:underline font-bold text-xs ml-auto pl-2">
                      Reset
                  </a>
              </div>
          @endif

          <!-- ============================================================= -->
          <!-- 1. MOBILE COMPACT FILTER BAR (Screen < sm / Mobile Phones)   -->
          <!-- ============================================================= -->
          <div class="sm:hidden mb-5 space-y-3">
              <!-- Live Search Input Box -->
              <div class="relative w-full">
                  <input type="text" 
                         name="search" 
                         x-model="filterState.search" 
                         @input.debounce.400ms="applyFilter()" 
                         placeholder="Cari posisi atau dinas..." 
                         class="w-full pl-11 pr-10 py-3.5 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-2xl text-sm font-bold text-slate-800 dark:text-gray-100 placeholder-slate-400 dark:placeholder-gray-500 shadow-2xs focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                      <i class="fas fa-search text-sm text-teal-600 dark:text-teal-400"></i>
                  </div>
                  <button type="button" x-show="filterState.search && filterState.search.length > 0" @click="filterState.search = ''; applyFilter()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                      <i class="fas fa-times-circle text-sm"></i>
                  </button>
              </div>

              <!-- Filter Trigger Button & Compact Sort -->
              <div class="flex items-center gap-2.5">
                  <!-- Bottom Sheet Filter Opener -->
                  <button type="button" 
                          @click="openMobileFilter()" 
                          class="flex-1 py-3 px-4 rounded-2xl border text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 shadow-2xs active:scale-95 transition"
                          :class="activeFilterCount() > 0 ? 'bg-teal-600 text-white border-teal-600 shadow-md shadow-teal-600/25' : 'bg-white dark:bg-gray-800 text-slate-700 dark:text-gray-200 border-slate-200 dark:border-gray-700'">
                      <i class="fas fa-sliders text-xs" :class="activeFilterCount() > 0 ? 'text-white' : 'text-teal-600 dark:text-teal-400'"></i>
                      <span>Filter Lengkap</span>
                      <template x-if="activeFilterCount() > 0">
                          <span class="px-2 py-0.5 rounded-full bg-white text-teal-700 text-[10px] font-black shadow-2xs" x-text="activeFilterCount()"></span>
                      </template>
                  </button>

                  <!-- Compact Sorting Dropdown -->
                  <div class="relative group shrink-0">
                      <select name="sort" x-model="filterState.sort" @change="applyFilter()" class="pl-8 pr-7 py-3 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-2xl text-xs font-bold text-slate-700 dark:text-gray-200 appearance-none shadow-2xs cursor-pointer focus:ring-2 focus:ring-teal-500">
                          <option value="latest">✨ Terbaru</option>
                          <option value="deadline_asc">⏰ Batas Waktu</option>
                          <option value="quota_desc">💺 Kuota</option>
                      </select>
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                          <i class="fas fa-arrow-down-short-wide text-[11px] text-teal-600 dark:text-teal-400"></i>
                      </div>
                      <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                          <i class="fas fa-chevron-down text-[9px]"></i>
                      </div>
                  </div>
              </div>
          </div>

          <!-- ============================================================= -->
          <!-- 2. DESKTOP FILTER DOCK CARD (Screen >= sm / Tablets & Desktops)-->
          <!-- ============================================================= -->
          <div class="reveal hidden sm:block bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-[2.5rem] shadow-xs border border-slate-100 dark:border-gray-700 mb-8 w-full" style="--reveal-delay: 100ms" x-intersect.once="$el.classList.add('revealed')">
              <form action="{{ route('home') }}#lowongan" method="GET" id="filter-form" @submit.prevent="applyFilter()" class="w-full">
                  @if(request('search'))
                      <input type="hidden" name="search" value="{{ request('search') }}" x-model="filterState.search">
                  @endif

                  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 lg:gap-5 items-end w-full">
                      <!-- Select Instansi -->
                      <div class="lg:col-span-4 w-full">
                          <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase mb-2 ml-1.5 tracking-wider flex items-center gap-2">
                              <i class="fas fa-building text-teal-600 dark:text-teal-400"></i> Instansi / Dinas
                          </label>
                          <div class="relative w-full group">
                              <select name="instansi_id" x-model="filterState.instansi_id" @change="applyFilter()" class="w-full pl-4 pr-10 py-3.5 border border-slate-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm font-bold bg-slate-50/50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-900 transition duration-300 appearance-none cursor-pointer text-slate-800 dark:text-gray-100 shadow-xs [color-scheme:dark]">
                                  <option value="" class="bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100">🏢 Semua Instansi</option>
                                  @foreach($instansis as $instansi)
                                      <option value="{{ $instansi->id }}" {{ request('instansi_id') == $instansi->id ? 'selected' : '' }} class="bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100">
                                          {{ Str::limit($instansi->nama_dinas, 40) }}
                                      </option>
                                  @endforeach
                              </select>
                              <span class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400 dark:text-gray-500 transition-colors group-hover:text-teal-600 dark:group-hover:text-teal-400">
                                  <i class="fas fa-chevron-down text-xs"></i>
                              </span>
                          </div>
                      </div>

                      <!-- Select Rumpun Keilmuan -->
                      <div class="lg:col-span-3 w-full">
                          <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase mb-2 ml-1.5 tracking-wider flex items-center gap-2">
                              <i class="fas fa-layer-group text-teal-600 dark:text-teal-400"></i> Rumpun Keilmuan
                          </label>
                          <div class="relative w-full group">
                              <select name="major_category_id" x-model="filterState.major_category_id" @change="applyFilter()" class="w-full pl-4 pr-10 py-3.5 border border-slate-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm font-bold bg-slate-50/50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-900 transition duration-300 appearance-none cursor-pointer text-slate-800 dark:text-gray-100 shadow-xs [color-scheme:dark]">
                                  <option value="" class="bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100">🌐 Semua Rumpun</option>
                                  @if(isset($majorCategories))
                                      @foreach($majorCategories as $cat)
                                          <option value="{{ $cat->id }}" {{ request('major_category_id') == $cat->id ? 'selected' : '' }} class="bg-white dark:bg-gray-900 text-slate-800 dark:text-gray-100">
                                              {{ $cat->name }} ({{ $cat->code }})
                                          </option>
                                      @endforeach
                                  @endif
                              </select>
                              <span class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400 dark:text-gray-500 transition-colors group-hover:text-teal-600 dark:group-hover:text-teal-400">
                                  <i class="fas fa-chevron-down text-xs"></i>
                              </span>
                          </div>
                      </div>

                      <!-- Input Jurusan / Keyword -->
                      <div class="lg:col-span-3 w-full">
                          <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase mb-2 ml-1.5 tracking-wider flex items-center gap-2">
                              <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400"></i> Cari Jurusan / Posisi
                          </label>
                          <div class="relative w-full">
                              <input type="text" name="jurusan" id="jurusan-input" x-model="filterState.jurusan" @input.debounce.400ms="applyFilter()" value="{{ request('jurusan') }}" placeholder="Contoh: Informatika, Akuntansi..." 
                                  class="w-full px-4 py-3.5 border border-slate-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm font-bold bg-slate-50/50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-900 transition duration-300 text-slate-800 dark:text-gray-100 placeholder-slate-400 dark:placeholder-gray-500 shadow-xs">
                          </div>
                      </div>

                      <!-- Urutkan (Sorting) -->
                      <div class="lg:col-span-2 w-full flex flex-col gap-2">
                          <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase mb-2 ml-1.5 tracking-wider flex items-center gap-2">
                              <i class="fas fa-sort text-teal-600 dark:text-teal-400"></i> Urutan
                          </label>
                          <div class="relative w-full group">
                              <select name="sort" x-model="filterState.sort" @change="applyFilter()" class="w-full pl-3 pr-8 py-3.5 border border-slate-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-xs font-bold bg-slate-50/50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-900 transition duration-300 appearance-none cursor-pointer text-slate-800 dark:text-gray-100 shadow-xs [color-scheme:dark]">
                                  <option value="latest" {{ request('sort') == 'latest' || !request('sort') ? 'selected' : '' }}>✨ Terbaru</option>
                                  <option value="deadline_asc" {{ request('sort') == 'deadline_asc' ? 'selected' : '' }}>⏰ Batas Terdekat</option>
                                  <option value="quota_desc" {{ request('sort') == 'quota_desc' ? 'selected' : '' }}>💺 Kuota Terbanyak</option>
                              </select>
                              <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400 dark:text-gray-500 transition-colors group-hover:text-teal-600 dark:group-hover:text-teal-400">
                                  <i class="fas fa-chevron-down text-[10px]"></i>
                              </span>
                          </div>
                      </div>
                  </div>
              </form>
          </div>

          <!-- ============================================================= -->
          <!-- 3. MOBILE FILTER BOTTOM SHEET MODAL (Screen < sm)             -->
          <!-- ============================================================= -->
          <template x-teleport="body">
              <div x-show="mobileFilterOpen" 
                   x-cloak
                   @keydown.escape.window="closeMobileFilter()"
                   class="fixed inset-0 z-[9998] sm:hidden overflow-hidden" 
                   role="dialog" 
                   aria-modal="true">
                  
                  <!-- Backdrop Overlay -->
                  <div x-show="mobileFilterOpen" 
                       x-transition:enter="ease-out duration-300" 
                       x-transition:enter-start="opacity-0" 
                       x-transition:enter-end="opacity-100" 
                       x-transition:leave="ease-in duration-200" 
                       x-transition:leave-start="opacity-100" 
                       x-transition:leave-end="opacity-0" 
                       @click="closeMobileFilter()"
                       class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm transition-opacity"></div>

                  <!-- Slide-Up Bottom Sheet Panel -->
                  <div class="fixed inset-x-0 bottom-0 max-h-[88vh] bg-white dark:bg-gray-900 border-t border-slate-200/80 dark:border-gray-800 shadow-2xl rounded-t-[2.5rem] flex flex-col z-10 overflow-hidden"
                       x-show="mobileFilterOpen"
                       x-transition:enter="transition ease-out duration-300 transform"
                       x-transition:enter-start="translate-y-full"
                       x-transition:enter-end="translate-y-0"
                       x-transition:leave="transition ease-in duration-200 transform"
                       x-transition:leave-start="translate-y-0"
                       x-transition:leave-end="translate-y-full"
                       @click.stop>
                      
                      <!-- Grab Handle & Header -->
                      <div class="px-6 pt-3 pb-4 border-b border-slate-100 dark:border-gray-800 text-center shrink-0">
                          <div class="w-12 h-1.5 bg-slate-300 dark:bg-gray-700 rounded-full mx-auto mb-3"></div>
                          <div class="flex items-center justify-between">
                              <div class="flex items-center gap-2.5">
                                  <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950/80 text-teal-600 dark:text-teal-400 flex items-center justify-center shadow-2xs">
                                      <i class="fas fa-sliders text-xs"></i>
                                  </div>
                                  <h3 class="text-base font-black text-slate-800 dark:text-white font-display">Filter Lowongan</h3>
                              </div>
                              <button @click="closeMobileFilter()" type="button" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-gray-400 flex items-center justify-center active:scale-95 transition">
                                  <i class="fas fa-times text-xs"></i>
                              </button>
                          </div>
                      </div>

                      <!-- Bottom Sheet Body Form -->
                      <div class="p-6 overflow-y-auto space-y-4 flex-grow overscroll-contain">
                          <!-- Select Instansi -->
                          <div>
                              <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                  🏢 Instansi / Dinas
                              </label>
                              <select x-model="tempFilter.instansi_id" class="w-full px-4 py-3.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-2xl text-xs font-bold text-slate-800 dark:text-gray-100 focus:ring-2 focus:ring-teal-500 [color-scheme:dark]">
                                  <option value="">🏢 Semua Instansi</option>
                                  @foreach($instansis as $instansi)
                                      <option value="{{ $instansi->id }}">{{ $instansi->nama_dinas }}</option>
                                  @endforeach
                              </select>
                          </div>

                          <!-- Select Rumpun Keilmuan -->
                          <div>
                              <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                  🌐 Rumpun Keilmuan
                              </label>
                              <select x-model="tempFilter.major_category_id" class="w-full px-4 py-3.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-2xl text-xs font-bold text-slate-800 dark:text-gray-100 focus:ring-2 focus:ring-teal-500 [color-scheme:dark]">
                                  <option value="">🌐 Semua Rumpun</option>
                                  @if(isset($majorCategories))
                                      @foreach($majorCategories as $cat)
                                          <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                                      @endforeach
                                  @endif
                              </select>
                          </div>

                          <!-- Input Jurusan -->
                          <div>
                              <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                  🎓 Spesifikasi Jurusan
                              </label>
                              <input type="text" x-model="tempFilter.jurusan" placeholder="Contoh: Informatika, Akuntansi..." class="w-full px-4 py-3.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-2xl text-xs font-bold text-slate-800 dark:text-gray-100 focus:ring-2 focus:ring-teal-500 placeholder-slate-400">
                          </div>

                          <!-- Select Urutan -->
                          <div>
                              <label class="block text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                  ⇅ Urutan Hasil
                              </label>
                              <select x-model="tempFilter.sort" class="w-full px-4 py-3.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-2xl text-xs font-bold text-slate-800 dark:text-gray-100 focus:ring-2 focus:ring-teal-500 [color-scheme:dark]">
                                  <option value="latest">✨ Terbaru</option>
                                  <option value="deadline_asc">⏰ Batas Waktu Terdekat</option>
                                  <option value="quota_desc">💺 Kuota Terbanyak</option>
                              </select>
                          </div>
                      </div>

                      <!-- Sticky Bottom Sheet Footer -->
                      <div class="p-5 border-t border-slate-100 dark:border-gray-800 bg-slate-50/95 dark:bg-gray-900/95 backdrop-blur-md flex items-center gap-3 shrink-0 pb-[calc(1rem+env(safe-area-inset-bottom,0px))]">
                          <button type="button" @click="resetMobileFilter()" class="py-3.5 px-5 bg-white dark:bg-gray-800 text-rose-600 dark:text-rose-400 border border-slate-200 dark:border-gray-700 rounded-2xl font-bold text-xs uppercase tracking-wider active:scale-95 transition shadow-2xs">
                              Reset
                          </button>
                          <button type="button" @click="applyMobileFilter()" class="flex-1 py-3.5 px-6 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-md shadow-teal-600/30 active:scale-95 transition text-center">
                              Terapkan Filter
                          </button>
                      </div>
                  </div>
              </div>
          </template>

          <!-- Quick Filter Scrollable Pills (Touch-Swipeable Ribbon on Mobile) -->
          <div class="reveal flex items-center gap-2.5 sm:gap-3.5 overflow-x-auto pb-3 mb-8 no-scrollbar scroll-smooth snap-x snap-mandatory w-full max-w-full -mx-4 px-4 sm:mx-0 sm:px-0" style="--reveal-delay: 200ms" x-intersect.once="$el.classList.add('revealed')">
              <span class="text-[11px] sm:text-xs font-black text-slate-400 dark:text-gray-400 uppercase tracking-wider shrink-0 mr-1 flex items-center">
                  <i class="fas fa-bolt text-amber-500 mr-2"></i> Filter Cepat:
              </span>
              <button type="button" @click="setQuickJurusan('')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ !request('jurusan') && !request('instansi_id') && !request('major_category_id') && !request('search') ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                  ✨ Semua Posisi
              </button>
              <button type="button" @click="setQuickJurusan('Informatika')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'Informatika') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                  💻 Informatika & Komputer
              </button>
              <button type="button" @click="setQuickJurusan('Akuntansi')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'Akuntansi') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                  📊 Akuntansi & Keuangan
              </button>
              <button type="button" @click="setQuickJurusan('Administrasi')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'Administrasi') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                  🏛️ Administrasi & Perkantoran
              </button>
              <button type="button" @click="setQuickJurusan('Desain')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'Desain') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                  🎨 Desain & Multimedia
              </button>
              <button type="button" @click="setQuickJurusan('Hukum')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'Hukum') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                  ⚖️ Hukum & Legal
              </button>
              <button type="button" @click="setQuickJurusan('Kesehatan')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'Kesehatan') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                  🏥 Medis & Kesehatan
              </button>
              <button type="button" @click="setQuickJurusan('SMK')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'SMK') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                  🏫 Khusus SMK
              </button>
              <button type="button" @click="setQuickJurusan('S1')" class="snap-start shrink-0 px-4 py-2.5 rounded-full text-xs font-extrabold transition duration-200 active:scale-95 {{ stripos(request('jurusan'), 'S1') !== false ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 shadow-xs' }}">
                  🎓 Mahasiswa (S1/D3)
              </button>
          </div>

          <!-- Skeleton Loading State (Shown During Live Fetching) -->
          <div x-show="isLoading" x-cloak class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 w-full animate-pulse">
              @for($i = 0; $i < 6; $i++)
                  <div class="bg-white dark:bg-gray-800 rounded-[2rem] border border-slate-200/80 dark:border-gray-700 p-6 flex flex-col h-[340px] justify-between shadow-xs">
                      <div class="space-y-4">
                          <div class="flex justify-between items-center">
                              <div class="h-5 w-20 bg-slate-200 dark:bg-gray-700 rounded-lg"></div>
                              <div class="h-5 w-24 bg-slate-200 dark:bg-gray-700 rounded-lg"></div>
                          </div>
                          <div class="flex items-center gap-3">
                              <div class="w-12 h-12 rounded-2xl bg-slate-200 dark:bg-gray-700 shrink-0"></div>
                              <div class="space-y-2 flex-grow">
                                  <div class="h-5 w-3/4 bg-slate-200 dark:bg-gray-700 rounded"></div>
                                  <div class="h-3 w-1/2 bg-slate-200 dark:bg-gray-700 rounded"></div>
                              </div>
                          </div>
                          <div class="h-10 w-full bg-slate-100 dark:bg-gray-700/50 rounded-xl"></div>
                      </div>
                      <div class="h-11 w-full bg-slate-200 dark:bg-gray-700 rounded-2xl"></div>
                  </div>
              @endfor
          </div>

          <!-- Vacancies Card Grid (Live Results) -->
          <div x-show="!isLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 w-full">
              @forelse($lowongans as $loker)
                  @if($loker->kuota > 0)
                      @php
                          $isClosingSoon = false;
                          if (!empty($loker->batas_daftar)) {
                              $deadline = \Carbon\Carbon::parse($loker->batas_daftar);
                              $diffDays = now()->diffInDays($deadline, false);
                              $isClosingSoon = ($diffDays >= 0 && $diffDays <= 7);
                          }
                      @endphp
                      <div x-data="{ showModal: false, copied: false }" 
                           x-init="$watch('showModal', value => { document.body.classList.toggle('overflow-hidden', value) })"
                           class="reveal h-full flex flex-col w-full" 
                           style="--reveal-delay: {{ ($loop->index % 3) * 120 }}ms" 
                           x-intersect.once="$el.classList.add('revealed')">
                          <!-- Job Card Wrapper -->
                          <div @click="showModal = true" class="cursor-pointer group bg-white dark:bg-gray-800 rounded-[2rem] border border-slate-200/80 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:border-teal-300 dark:hover:border-teal-500 hover:-translate-y-1 active:scale-[0.99] transition-all duration-300 flex flex-col h-full relative shadow-xs w-full">
                          
                              <!-- Card Header Bar -->
                              <div class="px-6 pt-6 pb-4 flex items-center justify-between gap-3 border-b border-slate-50 dark:border-gray-700/60 shrink-0">
                                  <div class="flex flex-wrap items-center gap-2">
                                      <span class="inline-flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60 text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-wider">
                                          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                                          {{ $loker->status }}
                                      </span>

                                      @if($isClosingSoon)
                                          <span class="inline-flex items-center gap-1 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/60 text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-wider animate-pulse">
                                              ⏰ Segera Ditutup
                                          </span>
                                      @endif

                                      @if($loker->kuota < 3)
                                          <span class="inline-flex items-center gap-1 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/60 text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-wider">
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
                                      @if($loker->requiredMajorCategory)
                                          <span class="inline-flex items-center px-3 py-1 rounded-xl text-[11px] font-bold bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60 max-w-full">
                                              <i class="fas fa-layer-group mr-2 text-teal-600 dark:text-teal-400 shrink-0"></i>
                                              <span class="truncate">{{ $loker->requiredMajorCategory->name }}</span>
                                          </span>
                                      @endif
                                      @if($loker->required_major && (!$loker->requiredMajorCategory || $loker->required_major !== $loker->requiredMajorCategory->name))
                                          <span class="inline-flex items-center px-3 py-1 rounded-xl text-[11px] font-bold bg-slate-100 dark:bg-gray-900 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-700 max-w-full">
                                              <i class="fas fa-graduation-cap mr-2 text-teal-600 dark:text-teal-400 shrink-0"></i>
                                              <span class="truncate" title="{{ $loker->required_major }}">{{ $loker->required_major }}</span>
                                          </span>
                                      @endif
                                      @if(preg_match('/SMA|SMK/i', (string) $loker->required_major))
                                          <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-black bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60 shrink-0">
                                              🎒 SMA/SMK
                                          </span>
                                      @endif
                                  </div>

                                  <!-- Excerpt description -->
                                  <div class="prose prose-sm text-slate-500 dark:text-gray-400 text-xs sm:text-sm leading-relaxed line-clamp-2 mb-6 font-medium">
                                      {{ strip_tags($loker->deskripsi) }}
                                  </div>
                              </div>

                              <!-- Action buttons footer -->
                              <div class="p-5 pt-3 bg-slate-50 dark:bg-gray-900 border-t border-slate-100 dark:border-gray-700/60 mt-auto shrink-0 flex items-center justify-between gap-3">
                                  @auth
                                      @if(auth()->user()->hasPortalRole('peserta'))
                                          @php
                                              $isMatch = $loker->matchesUser(auth()->user());
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
                                      @elseif(auth()->user()->hasPortalRole(['admin_kota', 'admin_instansi']))
                                          <button disabled class="w-full text-center text-xs font-bold text-gray-500 dark:text-gray-400 py-3.5 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl uppercase tracking-wider">Pratinjau Admin</button>
                                      @endif
                                  @else
                                      <a @click.stop href="{{ route('peserta.daftar.form', $loker->id) }}" class="w-full bg-slate-900 dark:bg-teal-600 hover:bg-teal-600 dark:hover:bg-teal-500 text-white py-3.5 px-4 rounded-2xl font-bold shadow-md active:scale-98 transition text-center text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                                          <span>Masuk & Lamar</span>
                                          <i class="fas fa-arrow-right text-xs"></i>
                                      </a>
                                  @endauth
                              </div>
                          </div>

                          <!-- Detail Loker Pop-Up Modal -->
                          <template x-teleport="body">
                              <div x-show="showModal" 
                                   x-cloak
                                   @keydown.escape.window="showModal = false"
                                   class="fixed inset-0 z-[9999] overflow-y-auto overscroll-contain" 
                                   role="dialog" 
                                   aria-modal="true"
                                   aria-labelledby="modal-title-{{ $loker->id }}">
                                  
                                  <!-- Backdrop overlay -->
                                  <div x-show="showModal" 
                                       x-transition:enter="ease-out duration-300" 
                                       x-transition:enter-start="opacity-0" 
                                       x-transition:enter-end="opacity-100" 
                                       x-transition:leave="ease-in duration-200" 
                                       x-transition:leave-start="opacity-100" 
                                       x-transition:leave-end="opacity-0" 
                                       class="fixed inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity" 
                                       @click="showModal = false"
                                       aria-hidden="true"></div>

                                  <!-- Pop Up Modal Center Container (Adaptive Bottom Sheet on Mobile) -->
                                  <div class="min-h-full flex items-center justify-center p-3 sm:p-6 text-center max-sm:p-0 max-sm:items-end">
                                      <div x-show="showModal" 
                                           x-transition:enter="transition ease-out duration-300 transform" 
                                           x-transition:enter-start="opacity-0 scale-95 translate-y-4 max-sm:translate-y-full max-sm:scale-100" 
                                           x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                                           x-transition:leave="transition ease-in duration-200 transform" 
                                           x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                                           x-transition:leave-end="opacity-0 scale-95 translate-y-4 max-sm:translate-y-full max-sm:scale-100"
                                           @click.stop
                                           class="relative bg-white dark:bg-gray-800 rounded-[2rem] sm:rounded-[2.5rem] max-sm:rounded-t-[2.5rem] max-sm:rounded-b-none shadow-2xl w-full max-w-2xl max-h-[90vh] max-sm:max-h-[92vh] overflow-hidden flex flex-col z-10 transition-all text-left border border-slate-200/80 dark:border-gray-700 overscroll-contain">
                                          
                                          <!-- Mobile Bottom Sheet Grab Handle -->
                                          <div class="w-12 h-1.5 bg-slate-300 dark:bg-gray-600 rounded-full mx-auto mt-3 mb-1 sm:hidden"></div>

                                          <!-- Header Pop Up -->
                                          <div class="px-5 sm:px-8 py-3.5 sm:py-5 border-b border-slate-100 dark:border-gray-700/80 bg-gray-50/80 dark:bg-gray-900/80 backdrop-blur-md shrink-0 flex justify-between items-center">
                                              <div class="flex items-center gap-3">
                                                  <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl bg-teal-500/10 dark:bg-teal-500/20 text-teal-600 dark:text-teal-400 flex items-center justify-center border border-teal-500/20 shadow-xs shrink-0">
                                                      <i class="fas fa-briefcase text-xs sm:text-sm"></i>
                                                  </div>
                                                  <div>
                                                      <span class="text-[9px] sm:text-[10px] font-extrabold text-teal-600 dark:text-teal-400 uppercase tracking-widest block">Informasi Lowongan</span>
                                                      <h3 id="modal-title-{{ $loker->id }}" class="text-sm sm:text-lg font-black text-slate-800 dark:text-gray-100 leading-tight">
                                                          Detail Lowongan Magang
                                                      </h3>
                                                  </div>
                                              </div>

                                              <div class="flex items-center gap-2">
                                                  <!-- Copy / Share Link Button -->
                                                  <button @click="navigator.clipboard.writeText('{{ route('lowongan.show', $loker->id) }}'); copied = true; window.dispatchEvent(new CustomEvent('notify', { detail: 'Tautan lowongan berhasil disalin ke clipboard!' })); setTimeout(() => copied = false, 2500)" 
                                                          type="button" 
                                                          class="h-9 px-3 flex items-center gap-1.5 text-xs font-bold rounded-xl transition duration-200 border shadow-2xs"
                                                          :class="copied ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800' : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-700 border-slate-200 dark:border-gray-700'"
                                                          title="Salin tautan lowongan">
                                                      <i :class="copied ? 'fas fa-check text-emerald-500' : 'fas fa-share-nodes text-teal-600 dark:text-teal-400'"></i>
                                                      <span x-text="copied ? 'Tersalin' : 'Bagikan'" class="hidden sm:inline">Bagikan</span>
                                                  </button>

                                                  <button @click="showModal = false" type="button" class="w-9 h-9 flex items-center justify-center text-slate-400 dark:text-gray-500 hover:text-slate-700 dark:hover:text-gray-200 bg-white dark:bg-gray-800 hover:bg-slate-100 dark:hover:bg-gray-700 border border-slate-200 dark:border-gray-700 rounded-xl transition duration-200 shadow-xs" title="Tutup">
                                                      <i class="fas fa-times text-sm"></i>
                                                  </button>
                                              </div>
                                          </div>
                                          
                                          <!-- Body Pop Up Content -->
                                          <div class="px-6 sm:px-8 pt-6 pb-8 overflow-y-auto flex-grow space-y-6 custom-scrollbar overscroll-contain">
                                              
                                              <!-- Branding & Instansi Block -->
                                              @php
                                                  $cleanDinas = trim(str_ireplace(['dinas', 'badan', 'kantor', 'bagian', 'sekretariat'], '', $loker->instansi->nama_dinas));
                                                  $initials = strtoupper(substr($cleanDinas, 0, 2));
                                              @endphp
                                              <div class="flex flex-col sm:flex-row items-start gap-4 pb-2 border-b border-slate-100 dark:border-gray-700/60">
                                                  <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-teal-500/20 shrink-0">
                                                      {{ $initials }}
                                                  </div>
                                                  <div class="space-y-1 flex-grow">
                                                      <h4 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-gray-100 leading-tight">
                                                          {{ $loker->judul_posisi }}
                                                      </h4>
                                                      <p class="text-xs sm:text-sm font-bold text-teal-600 dark:text-teal-400 flex items-center gap-1.5">
                                                          <i class="fas fa-building text-xs"></i>
                                                          <span>{{ $loker->instansi->nama_dinas }}</span>
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
                                              <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                                  <!-- Status Card -->
                                                  <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-3.5 flex flex-col justify-between shadow-2xs">
                                                      <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Status</span>
                                                      <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-1 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-lg border border-emerald-200 dark:border-emerald-800/60 w-fit">
                                                          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>
                                                          {{ $loker->status }}
                                                      </span>
                                                  </div>

                                                  <!-- Kuota Card -->
                                                  <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-3.5 flex flex-col justify-between shadow-2xs">
                                                      <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Kapasitas Kursi</span>
                                                      <span class="text-xs font-bold text-slate-800 dark:text-gray-100 mt-1 flex items-center gap-1.5">
                                                          <i class="fas fa-users text-teal-600 dark:text-teal-400 text-[10px]"></i>
                                                          <span>{{ $loker->kuota }} Posisi</span>
                                                      </span>
                                                  </div>

                                                  <!-- Deadline Card -->
                                                  <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-3.5 flex flex-col justify-between shadow-2xs">
                                                      <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Batas Daftar</span>
                                                      <span class="text-xs font-bold text-slate-800 dark:text-gray-100 mt-1 flex items-center gap-1.5 truncate" title="{{ \Carbon\Carbon::parse($loker->batas_daftar)->translatedFormat('d F Y') }}">
                                                          <i class="fas fa-calendar-alt text-teal-600 dark:text-teal-400 text-[10px]"></i>
                                                          <span class="truncate">{{ \Carbon\Carbon::parse($loker->batas_daftar)->translatedFormat('d M Y') }}</span>
                                                      </span>
                                                  </div>

                                                  <!-- Kualifikasi Card -->
                                                  <div class="bg-slate-50 dark:bg-gray-900/60 border border-slate-100 dark:border-gray-700/80 rounded-2xl p-3.5 flex flex-col justify-between shadow-2xs">
                                                      <span class="text-[9px] font-extrabold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Kualifikasi</span>
                                                      <span class="text-xs font-bold text-slate-800 dark:text-gray-100 mt-1 truncate flex items-center gap-1.5" title="{{ $loker->required_major ?? 'Semua Jurusan' }}">
                                                          <i class="fas fa-graduation-cap text-teal-600 dark:text-teal-400 text-[10px]"></i>
                                                          <span class="truncate">{{ $loker->required_major ?? 'Semua' }}</span>
                                                      </span>
                                                  </div>
                                              </div>

                                              <!-- Detail Job Description -->
                                              <div class="space-y-3">
                                                  <h5 class="text-xs font-extrabold text-slate-800 dark:text-gray-100 uppercase tracking-wider flex items-center gap-2">
                                                      <i class="fas fa-file-lines text-teal-600 dark:text-teal-400"></i> Deskripsi Pekerjaan & Persyaratan
                                                  </h5>
                                                  <div class="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-gray-300 bg-slate-50 dark:bg-gray-900/50 p-5 rounded-2xl border border-slate-100 dark:border-gray-700/70 text-xs sm:text-sm font-medium leading-relaxed">
                                                      {!! \App\Services\HtmlSanitizer::clean($loker->deskripsi) ?: '<p class="text-slate-400 italic">Tidak ada deskripsi rinci.</p>' !!}
                                                  </div>
                                              </div>

                                              <!-- Detailed Office & Penanggung Jawab Section -->
                                              <div class="space-y-3">
                                                  <h5 class="text-xs font-extrabold text-slate-800 dark:text-gray-100 uppercase tracking-wider flex items-center gap-2">
                                                      <i class="fas fa-building-circle-check text-teal-600 dark:text-teal-400"></i> Informasi Kantor & Penempatan
                                                  </h5>
                                                  
                                                  <div class="bg-slate-50 dark:bg-gray-900/50 border border-slate-200/80 dark:border-gray-700/70 rounded-2xl p-4 sm:p-5 space-y-3.5 text-xs sm:text-sm">
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
                                                                  <a href="https://www.google.com/maps/search/?api=1&query={{ $loker->instansi->latitude }},{{ $loker->instansi->longitude }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-teal-600 dark:text-teal-400 font-bold hover:underline mt-2 text-xs">
                                                                      <span>Buka Google Maps</span>
                                                                      <i class="fas fa-external-link-alt text-[10px]"></i>
                                                                  </a>
                                                              </div>
                                                          </div>
                                                      @endif
                                                  </div>
                                              </div>
                                          </div>

                                          <!-- Footer Pop Up (Sticky Bottom on Mobile with iOS Safe Area) -->
                                          <div class="px-5 sm:px-8 py-3.5 sm:py-4 border-t border-slate-100 dark:border-gray-700 bg-gray-50/95 dark:bg-gray-900/95 backdrop-blur-md flex items-center justify-between gap-2.5 shrink-0 z-20 pb-[calc(0.875rem+env(safe-area-inset-bottom,0px))]">
                                              <a href="{{ route('lowongan.show', $loker->id) }}" class="text-slate-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 text-xs font-bold transition flex items-center gap-1.5 shrink-0" title="Buka tautan langsung lowongan ini">
                                                  <i class="fas fa-arrow-up-right-from-square text-[11px]"></i>
                                                  <span class="hidden sm:inline">Halaman Penuh</span>
                                              </a>

                                              <div class="flex items-center gap-2 sm:gap-3 flex-grow sm:flex-grow-0 justify-end">
                                                  <button @click="showModal = false" type="button" class="px-4 sm:px-5 py-3 bg-white hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-200 border border-slate-200 dark:border-gray-700 rounded-xl font-bold transition text-xs uppercase tracking-wider active:scale-95">
                                                      Tutup
                                                  </button>
                                                  
                                                  @auth
                                                      @if(auth()->user()->hasPortalRole('peserta'))
                                                          @if($isMatch ?? true)
                                                              <a href="{{ route('peserta.daftar.form', $loker->id) }}" class="flex-1 sm:flex-none bg-teal-600 hover:bg-teal-700 text-white px-5 sm:px-6 py-3 rounded-xl font-bold shadow-md transition text-xs uppercase tracking-wider flex items-center justify-center gap-2 active:scale-95">
                                                                  <span>Ajukan Lamaran</span>
                                                                  <i class="fas fa-arrow-right text-xs"></i>
                                                              </a>
                                                          @else
                                                              <button disabled class="flex-1 sm:flex-none bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-4 sm:px-5 py-3 rounded-xl font-bold cursor-not-allowed text-xs uppercase tracking-wider">
                                                                  <i class="fas fa-lock text-xs mr-1"></i> Syarat Tidak Sesuai
                                                              </button>
                                                          @endif
                                                      @elseif(auth()->user()->hasPortalRole(['admin_kota', 'admin_instansi']))
                                                          <button disabled class="px-4 sm:px-5 py-3 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 rounded-xl font-bold text-xs uppercase tracking-wider">Pratinjau Admin</button>
                                                      @endif
                                                  @else
                                                      <a href="{{ route('peserta.daftar.form', $loker->id) }}" class="flex-1 sm:flex-none bg-teal-600 hover:bg-teal-700 text-white px-5 sm:px-6 py-3 rounded-xl font-bold shadow-md transition text-xs uppercase tracking-wider flex items-center justify-center gap-2 active:scale-95">
                                                          <span>Masuk & Lamar</span>
                                                          <i class="fas fa-arrow-right text-xs"></i>
                                                      </a>
                                                  @endauth
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </template>
                      </div>
                  @endif
              @empty
                  <!-- Enhanced Empty State Lowongan -->
                  <div class="col-span-full py-16 sm:py-20 text-center bg-white dark:bg-gray-800/80 rounded-[2.5rem] border border-dashed border-slate-200 dark:border-gray-700 p-8 shadow-xs">
                      <div class="w-20 h-20 bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 rounded-3xl flex items-center justify-center mx-auto mb-4 border border-teal-100 dark:border-teal-900/50 shadow-inner">
                          <i class="fas fa-magnifying-glass text-3xl"></i>
                      </div>
                      <h3 class="text-lg sm:text-xl font-black text-slate-800 dark:text-gray-100">Lowongan Belum Ditemukan</h3>
                      <p class="text-slate-500 dark:text-gray-400 mt-2 max-w-md mx-auto text-xs sm:text-sm font-medium leading-relaxed">
                          Tidak ada posisi magang aktif yang sesuai dengan kombinasi filter atau kata kunci saat ini. Anda dapat mereset filter atau mencoba opsi alokasi cerdas.
                      </p>
                      
                      <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
                          <a href="{{ route('home') }}#lowongan" class="inline-flex items-center gap-2 bg-slate-900 dark:bg-teal-600 hover:bg-teal-600 dark:hover:bg-teal-500 text-white px-6 py-3 rounded-2xl text-xs font-bold transition shadow-md uppercase tracking-wider">
                              <i class="fas fa-undo text-xs"></i> Reset Semua Filter
                          </a>
                          <a href="{{ route('peserta.apply_automatic.form') }}" class="inline-flex items-center gap-2 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 px-6 py-3 rounded-2xl text-xs font-bold transition uppercase tracking-wider">
                              <i class="fas fa-wand-magic-sparkles text-xs"></i> Coba Penempatan Otomatis
                          </a>
                      </div>
                  </div>
              @endforelse
          </div>
          
          <!-- Laravel Pagination Links -->
          <div class="mt-12 sm:mt-16 w-full" id="lowongan-pagination" x-show="!isLoading">
              {{ $lowongans->links() }}
          </div>

          <!-- Alpine Grid Manager Script -->
          <script>
              function lowonganGridManager() {
                  return {
                      isLoading: false,
                      mobileFilterOpen: false,
                      filterState: {
                          search: '{{ request('search') }}',
                          instansi_id: '{{ request('instansi_id') }}',
                          major_category_id: '{{ request('major_category_id') }}',
                          jurusan: '{{ request('jurusan') }}',
                          sort: '{{ request('sort', 'latest') }}'
                      },
                      tempFilter: {
                          instansi_id: '{{ request('instansi_id') }}',
                          major_category_id: '{{ request('major_category_id') }}',
                          jurusan: '{{ request('jurusan') }}',
                          sort: '{{ request('sort', 'latest') }}'
                      },
                      initGrid() {
                          this.setupPaginationLinks();
                      },
                      activeFilterCount() {
                          let count = 0;
                          if (this.filterState.instansi_id) count++;
                          if (this.filterState.major_category_id) count++;
                          if (this.filterState.jurusan) count++;
                          if (this.filterState.sort && this.filterState.sort !== 'latest') count++;
                          return count;
                      },
                      openMobileFilter() {
                          this.tempFilter.instansi_id = this.filterState.instansi_id;
                          this.tempFilter.major_category_id = this.filterState.major_category_id;
                          this.tempFilter.jurusan = this.filterState.jurusan;
                          this.tempFilter.sort = this.filterState.sort || 'latest';
                          this.mobileFilterOpen = true;
                          document.body.classList.add('overflow-hidden');
                      },
                      closeMobileFilter() {
                          this.mobileFilterOpen = false;
                          document.body.classList.remove('overflow-hidden');
                      },
                      applyMobileFilter() {
                          this.filterState.instansi_id = this.tempFilter.instansi_id;
                          this.filterState.major_category_id = this.tempFilter.major_category_id;
                          this.filterState.jurusan = this.tempFilter.jurusan;
                          this.filterState.sort = this.tempFilter.sort;
                          this.closeMobileFilter();
                          this.applyFilter();
                      },
                      resetMobileFilter() {
                          this.tempFilter.instansi_id = '';
                          this.tempFilter.major_category_id = '';
                          this.tempFilter.jurusan = '';
                          this.tempFilter.sort = 'latest';
                          this.applyMobileFilter();
                      },
                      setQuickJurusan(jurusan) {
                          this.filterState.jurusan = jurusan;
                          this.applyFilter();
                      },
                      applyFilter() {
                          const params = new URLSearchParams();
                          if (this.filterState.search) params.append('search', this.filterState.search);
                          if (this.filterState.instansi_id) params.append('instansi_id', this.filterState.instansi_id);
                          if (this.filterState.major_category_id) params.append('major_category_id', this.filterState.major_category_id);
                          if (this.filterState.jurusan) params.append('jurusan', this.filterState.jurusan);
                          if (this.filterState.sort && this.filterState.sort !== 'latest') params.append('sort', this.filterState.sort);

                          const targetUrl = '{{ route('home') }}' + (params.toString() ? '?' + params.toString() : '') + '#lowongan';
                          
                          if (window.Turbo) {
                              window.Turbo.visit(targetUrl, { action: 'advance' });
                          } else {
                              window.location.href = targetUrl;
                          }
                      },
                      setupPaginationLinks() {
                          const paginationLinks = document.querySelectorAll('#lowongan-pagination a');
                          paginationLinks.forEach(link => {
                              try {
                                  const url = new URL(link.href, window.location.origin);
                                  url.hash = 'lowongan';
                                  link.href = url.toString();
                              } catch(e) {}
                          });
                      }
                  }
              }

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
