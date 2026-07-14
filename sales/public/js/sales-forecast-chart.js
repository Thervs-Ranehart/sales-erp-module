(function () {
    const registry = new Map();
    const money = (value) => new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP', maximumFractionDigits: 0 }).format(value);

    function read(root) {
        try {
            const data = JSON.parse(root.dataset.chartInitialData || 'null');
            const keys = ['labels', 'actual', 'forecast', 'forecastLow', 'forecastHigh'];
            return data && keys.every((key) => Array.isArray(data[key]) && data[key].length === data.labels.length) ? data : null;
        } catch (error) {
            console.warn('Sales forecast chart data is invalid.', error);
            return null;
        }
    }

    function create(root) {
        if (typeof Chart === 'undefined') return;
        const id = root.dataset.chartId;
        const canvas = document.getElementById(id);
        const data = read(root);
        if (!canvas || !data) return;
        if (registry.has(id)) registry.get(id).destroy();

        registry.set(id, new Chart(canvas, {
            type: 'line',
            data: { labels: data.labels, datasets: [
                { label: 'Forecast low', data: data.forecastLow, borderWidth: 0, pointRadius: 0, backgroundColor: 'rgba(83,71,206,.10)', fill: false },
                { label: 'Forecast confidence range', data: data.forecastHigh, borderWidth: 0, pointRadius: 0, backgroundColor: 'rgba(83,71,206,.10)', fill: '-1' },
                { label: 'Historical actual revenue', data: data.actual, borderColor: '#128B99', backgroundColor: 'transparent', borderWidth: 3, pointRadius: 3, pointHoverRadius: 7, tension: .35, spanGaps: false },
                { label: 'Forecast revenue', data: data.forecast, borderColor: '#5347CE', backgroundColor: 'transparent', borderWidth: 3, borderDash: [8, 6], pointRadius: 3, pointHoverRadius: 7, tension: .35, spanGaps: false },
            ]},
            options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, plugins: {
                legend: { position: 'top', align: 'end', labels: { usePointStyle: true, padding: 18, filter: (item) => item.text !== 'Forecast low' } },
                tooltip: { backgroundColor: '#0f172a', padding: 12, cornerRadius: 10, callbacks: { label: (context) => `${context.dataset.label}: ${money(context.raw ?? 0)}` } },
            }, scales: { x: { grid: { display: false }, ticks: { color: '#64748b' } }, y: { beginAtZero: true, ticks: { color: '#64748b', callback: money }, grid: { color: 'rgba(15,23,42,.08)' } } } },
        }));
    }

    const init = () => document.querySelectorAll('[data-component="sales-forecast-chart"]').forEach(create);
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
