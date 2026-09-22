@once
@push('scripts')
<script>
    /**
     * ReportCharts — helper loader & theming untuk grafik Chart.js pada modul laporan.
     * Memuat Chart.js 4.x dari CDN sekali saja, lalu menyediakan default warna
     * yang adaptif terhadap light/dark mode.
     */
    window.ReportCharts = window.ReportCharts || (function () {
        const CDN = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js';
        let loader = null;

        function load() {
            if (window.Chart) return Promise.resolve(window.Chart);
            if (!loader) {
                loader = new Promise(function (resolve, reject) {
                    const script = document.createElement('script');
                    script.src = CDN;
                    script.onload = function () { resolve(window.Chart); };
                    script.onerror = function () { loader = null; reject(new Error('Gagal memuat Chart.js dari CDN.')); };
                    document.head.appendChild(script);
                });
            }
            return loader;
        }

        function isDark() {
            return document.documentElement.classList.contains('dark');
        }

        function palette() {
            const dark = isDark();
            return {
                grid: dark ? 'rgba(148, 163, 184, 0.10)' : 'rgba(100, 116, 139, 0.12)',
                ticks: dark ? '#94a3b8' : '#64748b',
                legend: dark ? '#cbd5e1' : '#334155',
                tooltipBg: dark ? '#0f172a' : '#ffffff',
                tooltipBorder: dark ? 'rgba(148, 163, 184, 0.25)' : 'rgba(100, 116, 139, 0.25)',
                tooltipTitle: dark ? '#f1f5f9' : '#0f172a',
                tooltipBody: dark ? '#cbd5e1' : '#334155',
            };
        }

        function tooltip(p) {
            return {
                backgroundColor: p.tooltipBg,
                titleColor: p.tooltipTitle,
                bodyColor: p.tooltipBody,
                borderColor: p.tooltipBorder,
                borderWidth: 1,
                padding: 10,
                cornerRadius: 10,
                boxPadding: 4,
                titleFont: { weight: 'bold' },
            };
        }

        function truncate(label, max) {
            max = max || 28;
            if (!label) return '';
            return label.length > max ? label.slice(0, max - 1) + '…' : label;
        }

        async function render(canvasId, configFactory) {
            try {
                const Chart = await load();
                const canvas = document.getElementById(canvasId);
                if (!canvas) return null;
                if (canvas._reportChart) { canvas._reportChart.destroy(); }

                Chart.defaults.font.family = "'Inter', 'Figtree', ui-sans-serif, system-ui, sans-serif";
                Chart.defaults.color = palette().ticks;

                const p = palette();
                const chart = new Chart(canvas, configFactory(p, isDark()));
                canvas._reportChart = chart;
                return chart;
            } catch (err) {
                console.warn('[ReportCharts] Grafik dilewati:', err && err.message ? err.message : err);
                const canvas = document.getElementById(canvasId);
                if (canvas && canvas.parentElement) {
                    canvas.parentElement.innerHTML =
                        '<div class="h-full flex items-center justify-center text-center text-xs font-bold text-slate-400 dark:text-slate-500 px-4">' +
                        '<span><i class="fas fa-chart-line mr-1.5"></i>Grafik tidak tersedia (library gagal dimuat).</span></div>';
                }
                return null;
            }
        }

        return { render: render, isDark: isDark, palette: palette, tooltip: tooltip, truncate: truncate };
    })();
</script>
@endpush
@endonce
