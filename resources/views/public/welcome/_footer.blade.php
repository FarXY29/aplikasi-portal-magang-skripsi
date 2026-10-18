
     <!-- Footer Section -->
     <footer class="bg-slate-900 text-slate-300 dark:bg-slate-950 dark:text-slate-400 pt-16 pb-8 mt-auto border-t border-slate-800 dark:border-slate-900 pb-safe w-full">
         <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
             
             <!-- Footer Grid -->
             <div class="reveal grid grid-cols-1 md:grid-cols-12 gap-8 sm:gap-10 pb-12 border-b border-slate-800 dark:border-slate-900 text-left w-full" style="--reveal-delay: 0ms" x-intersect.once="$el.classList.add('revealed')">
                 <!-- Kolom 1 (Branding & Logo) -->
                 <div class="md:col-span-5 flex flex-col items-start gap-4">
                     <div class="flex items-center gap-3">
                         <div class="bg-white dark:bg-gray-800/10 rounded-2xl p-2.5 backdrop-blur-md border border-white/10 flex items-center justify-center">
                             <x-ui.application-logo class="w-10 h-10 fill-current text-teal-400" />
                         </div>
                         <div>
                             <h4 class="font-extrabold text-lg leading-tight tracking-tight">SiMagang Kota Banjarmasin</h4>
                             <span class="text-[10px] text-teal-400 font-extrabold uppercase tracking-widest mt-1 block">Pemerintah Kota Banjarmasin</span>
                         </div>
                     </div>
                     <p class="text-xs sm:text-sm text-slate-400 leading-relaxed mt-2 max-w-sm">
                         Portal pendaftaran dan manajemen program magang/praktik kerja industri secara online, terpadu, dan transparan di lingkup Pemerintah Kota Banjarmasin.
                     </p>
                 </div>

                 <!-- Kolom 2 (Navigasi Cepat) -->
                 <div class="md:col-span-3 flex flex-col items-start gap-4">
                     <h5 class="text-xs font-black uppercase tracking-widest text-teal-400">Navigasi</h5>
                     <ul class="space-y-2.5 text-xs sm:text-sm text-slate-400 font-medium">
                         <li><a href="#lowongan" class="hover:text-white transition duration-300">Cari Lowongan Magang</a></li>
                         <li><a href="#langkah" class="hover:text-white transition duration-300">Alur Pendaftaran</a></li>
                         <li><a href="#faq" class="hover:text-white transition duration-300">FAQ & Bantuan</a></li>
                     </ul>
                 </div>

                 <!-- Kolom 3 (Hubungi Kami) -->
                 <div class="md:col-span-4 flex flex-col items-start gap-4">
                     <h5 class="text-xs font-black uppercase tracking-widest text-teal-400">Hubungi Kami</h5>
                     <ul class="space-y-3 text-xs sm:text-sm text-slate-400">
                         <li class="flex items-start gap-3 leading-relaxed">
                             <i class="fas fa-map-marker-alt text-teal-500 mt-1 shrink-0"></i>
                             <span>Dinas Komunikasi, Informatika dan Statistik<br>Kota Banjarmasin, Kalimantan Selatan</span>
                         </li>
                         <li class="flex items-center gap-3">
                             <i class="fas fa-globe text-teal-500 shrink-0"></i>
                             <a href="https://diskominfotik.banjarmasinkota.go.id" target="_blank" class="hover:text-white transition duration-300">diskominfotik.banjarmasinkota.go.id</a>
                         </li>
                     </ul>
                 </div>
             </div>

             <!-- Footer Bottom Bar -->
             <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left text-[11px] sm:text-xs text-slate-500 w-full">
                 <p>&copy; {{ date('Y') }} Dinas Komunikasi, Informatika dan Statistik Kota Banjarmasin. Hak Cipta Dilindungi.</p>
                 
                 <!-- Social Links -->
                 <div class="flex gap-3.5">
                     <a href="#" aria-label="Instagram" class="w-9 h-9 rounded-xl bg-white dark:bg-gray-800/5 hover:bg-teal-600 hover:text-white active:scale-95 transition-all duration-300 flex items-center justify-center text-slate-400">
                         <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                     </a>
                     <a href="#" aria-label="Facebook" class="w-9 h-9 rounded-xl bg-white dark:bg-gray-800/5 hover:bg-teal-600 hover:text-white active:scale-95 transition-all duration-300 flex items-center justify-center text-slate-400">
                         <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                     </a>
                     <a href="#" aria-label="Website Resmi" class="w-9 h-9 rounded-xl bg-white dark:bg-gray-800/5 hover:bg-teal-600 hover:text-white active:scale-95 transition-all duration-300 flex items-center justify-center text-slate-400"><i class="fas fa-globe text-sm"></i></a>
                 </div>
             </div>
         </div>
     </footer>
     
     <!-- Turbo Drive menangani prefetch & navigasi; instant.page dihapus untuk menghindari konflik. -->
     <script>
         function scrollToResults() {
             const params = new URLSearchParams(window.location.search);
             if (params.has('search') || params.has('instansi_id') || params.has('jurusan')) {
                 const element = document.getElementById('lowongan');
                 if (element) {
                     setTimeout(() => {
                         element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                     }, 50);
                 }
             }
         }
         document.addEventListener('DOMContentLoaded', scrollToResults);
         document.addEventListener('turbo:load', scrollToResults);

         // Intercept GET form submissions to prevent full-page white flash and use Turbo
         document.addEventListener('submit', function(e) {
             const form = e.target;
             if (form && form.getAttribute('method')?.toLowerCase() === 'get' && (form.id === 'search-form' || form.id === 'filter-form')) {
                 e.preventDefault();
                 
                 const url = new URL(form.action || window.location.href);
                 const formData = new FormData(form);
                 const params = new URLSearchParams();
                 
                 for (const [key, value] of formData.entries()) {
                     if (value !== '') {
                         params.append(key, value);
                     }
                 }
                 
                 // Merge parameters so search & filter parameters are preserved together
                 const currentParams = new URLSearchParams(window.location.search);
                 if (form.id === 'filter-form') {
                     if (currentParams.has('search')) {
                         params.set('search', currentParams.get('search'));
                     }
                 } else if (form.id === 'search-form') {
                     if (currentParams.has('instansi_id')) {
                         params.set('instansi_id', currentParams.get('instansi_id'));
                     }
                     if (currentParams.has('jurusan')) {
                         params.set('jurusan', currentParams.get('jurusan'));
                     }
                 }
                 
                 url.search = params.toString();
                 url.hash = 'lowongan';
                 
                 if (window.Turbo) {
                     window.Turbo.visit(url.toString(), { action: 'advance' });
                 } else {
                     window.location.href = url.toString();
                 }
             }
         });
     </script>