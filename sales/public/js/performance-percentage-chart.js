(function () {
    const chartRegistry = new Map();

    function readData(root) {
        try {
            const source = JSON.parse(root.dataset.chartInitialData || 'null');
            if (!source || !Array.isArray(source.labels) || !Array.isArray(source.values)) return null;
            if (source.labels.length !== source.values.length) return null;
            return source;
        } catch (error) {
            console.warn('Performance chart data is invalid.', error);
            return null;
        }
    }

    function colors(values) {
        return values.map((value) => {
            const percentage = Number(value);
            if (percentage <= 30) return 'rgba(239, 68, 68, 0.82)';
            if (percentage < 70) return 'rgba(244, 180, 0, 0.82)';
            return 'rgba(16, 185, 129, 0.82)';
        });
    }

    function createChart(root) {
        if (typeof Chart === 'undefined') return;

        const data = readData(root);
        const chartId = root.dataset.chartId;
        const canvas = document.getElementById(chartId);
        if (!data || !canvas) return;

        const existing = chartRegistry.get(chartId);
        if (existing) existing.destroy();

        const isLine = root.dataset.chartType === 'line';
        const dataset = {
            label: 'Achievement',
            data: data.values,
            borderWidth: isLine ? 3 : 1,
            borderColor: isLine ? '#5347CE' : colors(data.values),
            backgroundColor: isLine ? 'rgba(83, 71, 206, 0.14)' : colors(data.values),
            fill: isLine,
            tension: 0.35,
            pointBackgroundColor: '#5347CE',
            borderRadius: isLine ? 0 : 8,
            borderSkipped: false,
            barThickness: isLine ? undefined : 18,
        };

        chartRegistry.set(chartId, new Chart(canvas, {
            type: isLine ? 'line' : 'bar',
            data: { labels: data.labels, datasets: [dataset] },
            options: {
                indexAxis: isLine ? 'x' : 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#0f172a', titleColor: '#fff', bodyColor: '#e2e8f0', padding: 12, cornerRadius: 10, displayColors: false, callbacks: { label: (context) => `${context.raw ?? 0}% achievement` } },
                },
                scales: {
                    x: isLine ? { grid: { display: false }, ticks: { color: '#64748b' } } : { beginAtZero: true, suggestedMax: 100, border: { display: false }, grid: { color: 'rgba(148,163,184,.16)' }, ticks: { color: '#94a3b8', font: { size: 10 }, callback: (value) => `${value}%` } },
                    y: isLine ? { suggestedMin: 60, suggestedMax: 120, grid: { color: 'rgba(15,23,42,.07)' }, ticks: { color: '#64748b', callback: (value) => `${value}%` } } : { border: { display: false }, grid: { display: false }, ticks: { color: '#475569', font: { size: 10, weight: '600' }, autoSkip: false } },
                },
            },
            plugins: isLine ? [{
                id: 'targetReference',
                afterDraw(chart) {
                    const y = chart.scales.y.getPixelForValue(100);
                    const context = chart.ctx;
                    context.save();
                    context.setLineDash([6, 5]);
                    context.strokeStyle = '#10B981';
                    context.beginPath();
                    context.moveTo(chart.chartArea.left, y);
                    context.lineTo(chart.chartArea.right, y);
                    context.stroke();
                    context.restore();
                },
            }] : [],
        }));
    }

    function init() {
        document.querySelectorAll('[data-component="performance-percentage-chart"]').forEach(createChart);
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
