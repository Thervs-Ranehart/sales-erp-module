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

    function maybeSortDescending(labels, values) {
        const pairs = labels.map((label, i) => ({
            label,
            value: values[i],
        }));

        // Highest → Lowest
        pairs.sort((a, b) => Number(b.value) - Number(a.value));

        return {
            labels: pairs.map((p) => p.label),
            values: pairs.map((p) => p.value),
        };
    }

    function readInitialDataFromRoot(rootEl) {
        if (!rootEl) return null;

        const raw = rootEl.getAttribute('data-chart-initial-data');
        if (!raw) return null;

        try {
            return JSON.parse(raw);
        } catch (e) {
            console.warn(
                'Sales by representative chart: invalid data-chart-initial-data JSON.',
                e
            );
            return null;
        }
    }

    const createChartRegistry = new Map();

    function createChartForRoot(rootEl) {
        if (typeof Chart === 'undefined') {
            console.warn('Chart.js not found.');
            return;
        }

        const chartId =
            rootEl.getAttribute('data-chart-id') ||
            'salesByRepresentativeHorizontalBarChart';

        const canvas = document.getElementById(chartId);

        if (!canvas) return;

        const sourceData = readInitialDataFromRoot(rootEl);
        const built = buildHorizontalBarData(sourceData);

        if (!built) {
            console.warn(
                'Sales by representative horizontal bar chart data missing or malformed.'
            );
            return;
        }

        const { labels, values } = maybeSortDescending(
            built.labels,
            built.values
        );

        const existing = createChartRegistry.get(chartId);

        if (existing && typeof existing.destroy === 'function') {
            existing.destroy();
        }

        const ctx = canvas.getContext('2d');

        const minValue = Math.min(...values.map((v) => Number(v)));
        const maxValue = Math.max(...values.map((v) => Number(v)));

        // Red → Yellow → Green color gradient
        const bgColors = values.map((v) => {
            const num = Number(v);

            if (num === minValue) return 'rgba(239, 68, 68, 0.35)';
            if (num === maxValue) return 'rgba(16, 185, 129, 0.35)';

            const t = (num - minValue) / (maxValue - minValue || 1);

            let r, g, b;

            if (t < 0.5) {
                // Red → Yellow
                const p = t * 2;

                r = 239;
                g = Math.round(68 + (193 - 68) * p);
                b = 68;
            } else {
                // Yellow → Green
                const p = (t - 0.5) * 2;

                r = Math.round(239 + (16 - 239) * p);
                g = Math.round(193 + (185 - 193) * p);
                b = Math.round(68 + (129 - 68) * p);
            }

            return `rgba(${r}, ${g}, ${b}, 0.35)`;
        });

        const borderColors = values.map((v) => {
            const num = Number(v);

            if (num === minValue) return 'rgba(239, 68, 68, 1)';
            if (num === maxValue) return 'rgba(16, 185, 129, 1)';

            const t = (num - minValue) / (maxValue - minValue || 1);

            let r, g, b;

            if (t < 0.5) {
                // Red → Yellow
                const p = t * 2;

                r = 239;
                g = Math.round(68 + (193 - 68) * p);
                b = 68;
            } else {
                // Yellow → Green
                const p = (t - 0.5) * 2;

                r = Math.round(239 + (16 - 239) * p);
                g = Math.round(193 + (185 - 193) * p);
                b = Math.round(68 + (129 - 68) * p);
            }

            return `rgba(${r}, ${g}, ${b}, 1)`;
        });

        const chart = new Chart(ctx, {
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
                    legend: {
                        display: false,
                    },

                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return formatPHP(context.raw ?? 0);
                            },
                        },
                    },
                },

                scales: {
                    x: {
                        grid: {
                            color: 'rgba(15, 23, 42, 0.08)',
                        },

                        ticks: {
                            callback: function (value) {
                                return formatPHP(value);
                            },

                            color: '#4b5563',
                        },
                    },

                    y: {
                        grid: {
                            display: false,
                        },

                        ticks: {
                            color: '#4b5563',

                            font: {
                                size: 12,
                            },
                        },
                    },
                },
            },
        });

        createChartRegistry.set(chartId, chart);
    }

    function init() {
        const roots = document.querySelectorAll(
            '[data-component="sales-by-representative-horizontal-bar"]'
        );

        roots.forEach((rootEl) => createChartForRoot(rootEl));
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();