<!-- Alpine Grid Manager Script (Live AJAX Filtering, Central Modal & URL State Sync) -->
<script>
    function lowonganGridManager() {
        return {
            isLoading: false,
            mobileFilterOpen: false,
            modalOpen: false,
            selectedJob: null,
            copied: false,
            debounceTimer: null,
            filterState: {
                search: '{{ request('search') }}',
                instansi_id: '{{ request('instansi_id') }}',
                major_category_id: '{{ request('major_category_id') }}',
                jurusan: '{{ request('jurusan') }}',
                sort: '{{ request('sort', 'latest') }}',
                filter_jurusan: '{{ request('filter_jurusan', $filterJurusanScope ?? '') }}'
            },
            tempFilter: {
                instansi_id: '{{ request('instansi_id') }}',
                major_category_id: '{{ request('major_category_id') }}',
                jurusan: '{{ request('jurusan') }}',
                sort: '{{ request('sort', 'latest') }}',
                filter_jurusan: '{{ request('filter_jurusan', $filterJurusanScope ?? '') }}'
            },
            initGrid() {
                this.setupPaginationLinks();
                this.setupChipLinks();

                // Listen to browser Back / Forward buttons
                window.addEventListener('popstate', () => {
                    this.readUrlParams();
                    const params = new URLSearchParams(window.location.search);
                    params.append('partial', 'grid');
                    const fetchUrl = '{{ route('home') }}?' + params.toString();
                    this.fetchGrid(fetchUrl, false);
                });
            },
            readUrlParams() {
                const params = new URLSearchParams(window.location.search);
                this.filterState.search = params.get('search') || '';
                this.filterState.instansi_id = params.get('instansi_id') || '';
                this.filterState.major_category_id = params.get('major_category_id') || '';
                this.filterState.jurusan = params.get('jurusan') || '';
                this.filterState.sort = params.get('sort') || 'latest';
                this.filterState.filter_jurusan = params.get('filter_jurusan') || '{{ ($userHasMajor ?? false) ? 'my_major' : 'all' }}';
            },
            activeFilterCount() {
                let count = 0;
                if (this.filterState.instansi_id) count++;
                if (this.filterState.major_category_id) count++;
                if (this.filterState.jurusan) count++;
                if (this.filterState.sort && this.filterState.sort !== 'latest') count++;
                return count;
            },
            openJobModal(job) {
                this.selectedJob = job;
                this.modalOpen = true;
                this.copied = false;
                document.body.classList.add('overflow-hidden');
            },
            closeJobModal() {
                this.modalOpen = false;
                this.selectedJob = null;
                document.body.classList.remove('overflow-hidden');
            },
            shareJob() {
                if (!this.selectedJob) return;
                const shareData = {
                    title: this.selectedJob.judul_posisi + ' - ' + this.selectedJob.instansi_nama,
                    text: 'Lowongan Magang: ' + this.selectedJob.judul_posisi + ' di ' + this.selectedJob.instansi_nama + '. Kuota: ' + this.selectedJob.kuota + ' posisi.',
                    url: this.selectedJob.show_url
                };

                if (navigator.share) {
                    navigator.share(shareData).catch(() => {});
                } else {
                    navigator.clipboard.writeText(this.selectedJob.show_url);
                    this.copied = true;
                    window.dispatchEvent(new CustomEvent('notify', { detail: 'Tautan lowongan berhasil disalin ke clipboard!' }));
                    setTimeout(() => { this.copied = false; }, 2500);
                }
            },
            openMobileFilter() {
                this.tempFilter.instansi_id = this.filterState.instansi_id;
                this.tempFilter.major_category_id = this.filterState.major_category_id;
                this.tempFilter.jurusan = this.filterState.jurusan;
                this.tempFilter.sort = this.filterState.sort || 'latest';
                this.tempFilter.filter_jurusan = this.filterState.filter_jurusan;
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
                this.filterState.filter_jurusan = this.tempFilter.filter_jurusan;
                this.closeMobileFilter();
                this.applyFilter();
            },
            resetMobileFilter() {
                this.tempFilter.instansi_id = '';
                this.tempFilter.major_category_id = '';
                this.tempFilter.jurusan = '';
                this.tempFilter.sort = 'latest';
                this.tempFilter.filter_jurusan = '{{ ($userHasMajor ?? false) ? 'my_major' : 'all' }}';
                this.applyMobileFilter();
            },
            setQuickJurusan(jurusan) {
                this.filterState.jurusan = jurusan;
                this.applyFilter();
            },
            setFilterJurusan(scope) {
                this.filterState.filter_jurusan = scope;
                this.applyFilter();
            },
            removeFilter(key) {
                if (key === 'search') this.filterState.search = '';
                if (key === 'instansi_id') this.filterState.instansi_id = '';
                if (key === 'major_category_id') this.filterState.major_category_id = '';
                if (key === 'jurusan') this.filterState.jurusan = '';
                if (key === 'sort') this.filterState.sort = 'latest';
                if (key === 'filter_jurusan') this.filterState.filter_jurusan = 'all';
                this.applyFilter();
            },
            resetAllFilters() {
                this.filterState.search = '';
                this.filterState.instansi_id = '';
                this.filterState.major_category_id = '';
                this.filterState.jurusan = '';
                this.filterState.sort = 'latest';
                this.filterState.filter_jurusan = '{{ ($userHasMajor ?? false) ? 'my_major' : 'all' }}';
                this.applyFilter();
            },
            applyFilter() {
                const params = new URLSearchParams();
                if (this.filterState.search) params.append('search', this.filterState.search);
                if (this.filterState.instansi_id) params.append('instansi_id', this.filterState.instansi_id);
                if (this.filterState.major_category_id) params.append('major_category_id', this.filterState.major_category_id);
                if (this.filterState.jurusan) params.append('jurusan', this.filterState.jurusan);
                if (this.filterState.sort && this.filterState.sort !== 'latest') params.append('sort', this.filterState.sort);
                if (this.filterState.filter_jurusan) params.append('filter_jurusan', this.filterState.filter_jurusan);

                const targetUrl = '{{ route('home') }}' + (params.toString() ? '?' + params.toString() : '') + '#lowongan';
                const fetchUrl = '{{ route('home') }}' + (params.toString() ? '?' + params.toString() + '&partial=grid' : '?partial=grid');

                this.fetchGrid(fetchUrl, true, targetUrl);
            },
            fetchGrid(fetchUrl, pushState = true, targetUrl = null) {
                this.isLoading = true;

                fetch(fetchUrl, {
                    headers: {
                        'X-Alpine-Fetch': '1',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Gagal memuat lowongan');
                    return response.text();
                })
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // 1. Update Cards Grid
                    const newGrid = doc.getElementById('vacancies-cards-grid');
                    const currentGrid = document.getElementById('vacancies-cards-grid');
                    if (newGrid && currentGrid) {
                        currentGrid.innerHTML = newGrid.innerHTML;
                    }

                    // 2. Update Pagination
                    const newPagination = doc.getElementById('lowongan-pagination-container');
                    const currentPagination = document.getElementById('lowongan-pagination-container');
                    if (newPagination && currentPagination) {
                        currentPagination.innerHTML = newPagination.innerHTML;
                    }

                    // 3. Update Results Count Badge
                    const newCount = doc.getElementById('lowongan-results-count');
                    const currentCount = document.getElementById('lowongan-results-count');
                    if (newCount && currentCount) {
                        currentCount.innerHTML = newCount.innerHTML;
                    }

                    // 4. Update Active Filter Chips
                    const newChips = doc.getElementById('active-filter-chips-container');
                    const currentChips = document.getElementById('active-filter-chips-container');
                    if (newChips && currentChips) {
                        currentChips.innerHTML = newChips.innerHTML;
                    }

                    // 5. Update Matching Qualification Banner
                    const newBanner = doc.getElementById('matching-qualification-banner');
                    const currentBanner = document.getElementById('matching-qualification-banner');
                    if (newBanner && currentBanner) {
                        currentBanner.innerHTML = newBanner.innerHTML;
                    }

                    // Sync URL in address bar without full page reload
                    if (pushState && targetUrl) {
                        window.history.pushState(this.filterState, '', targetUrl);
                    }

                    this.setupPaginationLinks();
                    this.setupChipLinks();
                })
                .catch(err => {
                    console.error('Error fetching grid partial:', err);
                    if (targetUrl) {
                        window.location.href = targetUrl;
                    }
                })
                .finally(() => {
                    this.isLoading = false;
                });
            },
            setupPaginationLinks() {
                const paginationContainer = document.getElementById('lowongan-pagination-container');
                if (!paginationContainer) return;

                const links = paginationContainer.querySelectorAll('a');
                links.forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        try {
                            const urlObj = new URL(link.href);
                            const params = new URLSearchParams(urlObj.search);

                            const targetUrl = '{{ route('home') }}?' + params.toString() + '#lowongan';
                            params.append('partial', 'grid');
                            const fetchUrl = '{{ route('home') }}?' + params.toString();

                            this.fetchGrid(fetchUrl, true, targetUrl);

                            // Smooth scroll to lowongan section
                            const section = document.getElementById('lowongan');
                            if (section) {
                                section.scrollIntoView({ behavior: 'smooth' });
                            }
                        } catch (err) {
                            window.location.href = link.href;
                        }
                    });
                });
            },
            setupChipLinks() {
                const chipsContainer = document.getElementById('active-filter-chips-container');
                if (!chipsContainer) return;

                chipsContainer.querySelectorAll('[data-filter-key]').forEach(el => {
                    el.addEventListener('click', (e) => {
                        e.preventDefault();
                        const key = el.getAttribute('data-filter-key');
                        this.removeFilter(key);
                    });
                });
            }
        };
    }
</script>
