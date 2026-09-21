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
            applyFilter() {
                const params = new URLSearchParams();
                if (this.filterState.search) params.append('search', this.filterState.search);
                if (this.filterState.instansi_id) params.append('instansi_id', this.filterState.instansi_id);
                if (this.filterState.major_category_id) params.append('major_category_id', this.filterState.major_category_id);
                if (this.filterState.jurusan) params.append('jurusan', this.filterState.jurusan);
                if (this.filterState.sort && this.filterState.sort !== 'latest') params.append('sort', this.filterState.sort);
                if (this.filterState.filter_jurusan) params.append('filter_jurusan', this.filterState.filter_jurusan);

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
