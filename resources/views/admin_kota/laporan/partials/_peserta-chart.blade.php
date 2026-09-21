@push('scripts')
<script>
(function () {
    const statusData = @json($chartStatusGlobal);

    function init() {
        window.ReportCharts.render('chart-status-global', function (p) {
            return {
                type: 'doughnut',
                data: {
                    labels: statusData.labels,
                    datasets: [{
                        data: statusData.values,
                        backgroundColor: statusData.colors,
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
