(function () {
    function formatPHP(value) {
        try {
            return new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP',
                maximumFractionDigits: 0,
            }).format(value);
        } catch (error) {
            return '₱' + Number(value).toLocaleString();
        }
    }

    function buildHorizontalBarData(source) {
        if (!source) return null;

        const labels = Array.isArray(source.labels) ? source.labels : null;
        const values = Array.isArray(source.values) ? source.values : null;
        if (!labels || !values) return null;
        if (labels.length !== values.length) return null;

        return {
            labels,
            values,
        };
    }

    function maybeSortAscending(labels, values) {
        // Ensures the chart is in ascending order by value.
        const pairs = labels.map((label, i) => ({ label, value: values[i] }));
        pairs.sort((a, b) => Number(a.value) - Number(b.value));
        return {
            labels: pairs.map((p) => p.label),
            values: pairs.map((p) => p.value),
        };
    }

    function createChart() {
        if (typeof Chart === 'undefined') {
            console.warn('Chart.js not found.');
            return;
        }

        const chartId = window.salesByRegionHorizontalBarChartId || 'salesByRegionHorizontalBarChart';
        const canvas = document.getElementById(chartId);
        if (!canvas) return;

        const sourceData = window.salesByRegionHorizontalBarData || null;
        const built = buildHorizontalBarData(sourceData);
        if (!built) {
            console.warn('Sales by region horizontal bar chart data missing or malformed.');
            return;
        }

        const { labels, values } = maybeSortAscending(built.labels, built.values);

        const existing = window.salesByRegionHorizontalBarChart;
        if (existing && typeof existing.destroy === 'function') {
            existing.destroy();
        }

        const ctx = canvas.getContext('2d');
        const minValue = Math.min(...values.map((v) => Number(v)));
        const maxValue = Math.max(...values.map((v) => Number(v)));

        // Battery-like colors: lowest = red, highest = green.
        const bgColors = values.map((v) => {
            const num = Number(v);
            if (num === minValue) return 'rgba(239, 68, 68, 0.35)'; // red
            if (num === maxValue) return 'rgba(16, 185, 129, 0.35)'; // green

            const t = (num - minValue) / (maxValue - minValue || 1); // 0..1
            const r = Math.round(239 + (16 - 239) * t);
            const g = Math.round(68 + (185 - 68) * t);
            const b = Math.round(68 + (129 - 68) * t);
            return `rgba(${r}, ${g}, ${b}, 0.35)`;
        });

        const borderColors = values.map((v) => {
            const num = Number(v);
            if (num === minValue) return 'rgba(239, 68, 68, 1)';
            if (num === maxValue) return 'rgba(16, 185, 129, 1)';

            const t = (num - minValue) / (maxValue - minValue || 1);
            const r = Math.round(239 + (16 - 239) * t);
            const g = Math.round(68 + (185 - 68) * t);
            const b = Math.round(68 + (129 - 68) * t);
            return `rgba(${r}, ${g}, ${b}, 1)`;
        });

        window.salesByRegionHorizontalBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Sales',
                        data: values,
                        backgroundColor: bgColors,
                        borderColor: borderColors,
                        borderWidth: 1,
                        borderRadius: 6,
                        maxBarThickness: 28,
                    },
                ],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const v = context.raw ?? 0;
                                return formatPHP(v);
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(15, 23, 42, 0.08)' },
                        ticks: {
                            callback: function (value) {
                                return formatPHP(value);
                            },
                            color: '#4b5563',
                        },
                    },
                    y: {
                        grid: { display: false },
                        ticks: {
                            color: '#4b5563',
                            font: { size: 12 },
                        },
                    },
                },
            },
        });

        if (canvas.parentElement) {
            canvas.parentElement.style.minHeight = '320px';
        }
    }

    function init() {
        createChart();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

