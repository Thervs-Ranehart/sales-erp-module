(function () {
    const registry = new Map();
    const money = (value) => new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP', maximumFractionDigits: 0 }).format(value);
    function read(root) { try { return JSON.parse(root.dataset.chartInitialData || 'null'); } catch (error) { console.warn('Dashboard chart data is invalid.', error); return null; } }
    function create(root) {
        if (typeof Chart === 'undefined') return;
        const id = root.dataset.chartId; const kind = root.dataset.chartKind; const data = read(root); const canvas = document.getElementById(id);
        if (!data || !canvas || !Array.isArray(data.labels)) return;
        const datasets = kind === 'target'
            ? [{ label: 'Sales Target', data: data.targetSeries, borderColor: '#10B981', backgroundColor: 'transparent', borderDash: [7,5], borderWidth: 3, tension: .35 }, { label: 'Actual Revenue', data: data.actualSeries, borderColor: '#5347CE', backgroundColor: 'rgba(83,71,206,.12)', fill: true, borderWidth: 3, tension: .35 }]
            : [{ label: 'Revenue', data: data.values, borderColor: '#128B99', backgroundColor: 'rgba(18,139,153,.14)', fill: true, borderWidth: 3, tension: .35 }];
        if (!datasets.every((dataset) => Array.isArray(dataset.data) && dataset.data.length === data.labels.length)) return;
        registry.get(id)?.destroy();
        registry.set(id, new Chart(canvas, { type: 'line', data: { labels: data.labels, datasets }, options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, plugins: { legend: { display: kind === 'target', labels: { usePointStyle: true } }, tooltip: { backgroundColor: '#0f172a', padding: 12, cornerRadius: 10, callbacks: { label: (context) => `${context.dataset.label}: ${money(context.raw ?? 0)}` } } }, scales: { x: { grid: { display: false }, ticks: { color: '#64748b' } }, y: { beginAtZero: true, grid: { color: 'rgba(15,23,42,.07)' }, ticks: { color: '#64748b', callback: money } } }, elements: { point: { radius: 3, hoverRadius: 7 } } } }));
    }
    const init = () => document.querySelectorAll('[data-component="dashboard-overview-chart"]').forEach(create);
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
