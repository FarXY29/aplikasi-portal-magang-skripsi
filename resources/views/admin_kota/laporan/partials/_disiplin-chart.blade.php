@push('scripts')
<script>
(function () {
    const topDisiplin = @json($chartTopDisiplin);
    const pelanggaran = @json($chartPelanggaran);

    function init() {
        window.ReportCharts.render('chart-top-disiplin', function (p) {
            return {
                type: 'bar',
                data: {
                    labels: topDisiplin.map(function (i) { return i.nama.length > 25 ? i.nama.substring(0, 22) + '...' : i.nama; }),
                    datasets: [{
                        label: 'Kedisiplinan (%)',
                        data: topDisiplin.map(function (i) { return i.nilai; }),
                        backgroundColor: '#10b981',
                        hoverBackgroundColor: '#059669',
                        borderRadius: 8,
                        borderSkipped: false,
                        maxBarThickness: 18,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: window.ReportCharts.tooltip(p),
                    },
                    scales: {
                        x: { min: 0, max: 100, grid: { color: p.grid }, ticks: { color: p.ticks, font: { size: 10, weight: 'bold' }, callback: function (v) { return v + '%'; } } },
                        y: { grid: { display: false }, ticks: { color: p.ticks, font: { size: 10, weight: 'bold' } } },
                    }
                }
            };
        });

        window.ReportCharts.render('chart-pelanggaran', function (p) {
            return {
                type: 'doughnut',
                data: {
                    labels: pelanggaran.labels,
                    datasets: [{
                        data: pelanggaran.values,
                        backgroundColor: pelanggaran.colors,
                        hoverOffset: 6,
                        borderWidth: 2,
                        borderColor: window.ReportCharts.isDark() ? '#161f33' : '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: p.legend, usePointStyle: true, boxWidth: 8, boxHeight: 8, padding: 16, font: { size: 10, weight: 'bold' } },
                        },
                        tooltip: window.ReportCharts.tooltip(p),
                    }
                }
            };
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    document.addEventListener('turbo:load', init);
})();
</script>
@endpush
