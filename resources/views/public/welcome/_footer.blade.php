<!-- Footer Section -->
<footer class="bg-slate-950 text-white pt-16 pb-8 mt-auto border-t border-slate-900 pb-safe w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        
        <!-- Footer Grid -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 pb-12 border-b border-slate-900 text-left w-full">
            <!-- Kolom 1 (Branding & Logo) -->
            <div class="md:col-span-5 flex flex-col items-start gap-4">
                <div class="flex items-center gap-3">
                    <div class="bg-white/5 rounded-2xl p-2.5 backdrop-blur-md border border-white/10 flex items-center justify-center">
                        <x-application-logo class="w-10 h-10 fill-current text-teal-400" />
                    </div>
                    <div>
                        <h4 class="font-extrabold text-lg leading-tight tracking-tight">SiMagang Kota Banjarmasin</h4>
                        <span class="text-[10px] text-teal-400 font-bold uppercase tracking-widest mt-1 block">Pemerintah Kota Banjarmasin</span>
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
                <a href="#" aria-label="Instagram" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-teal-600 hover:text-white transition-all duration-300 flex items-center justify-center text-slate-400"><i class="fab fa-instagram text-sm"></i></a>
                <a href="#" aria-label="Facebook" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-teal-600 hover:text-white transition-all duration-300 flex items-center justify-center text-slate-400"><i class="fab fa-facebook text-sm"></i></a>
                <a href="#" aria-label="Website Resmi" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-teal-600 hover:text-white transition-all duration-300 flex items-center justify-center text-slate-400"><i class="fas fa-globe text-sm"></i></a>
            </div>
        </div>
    </div>
</footer>
<script src="//instant.page/5.2.0" type="module" integrity="sha384-jnZyxPjiipYXnSU0ygqeac2q7CVYMbh84q0uHVRRxEtvFPiQYbXWUorga2aqZJ0z"></script>
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