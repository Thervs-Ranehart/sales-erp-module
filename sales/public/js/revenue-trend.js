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

    function buildChartData(source) {
        if (!source) return null;

        const labels = Array.isArray(source.labels) ? source.labels : null;
        const values = Array.isArray(source.values) ? source.values : null;

        if (!labels || !values) return null;
        if (labels.length !== values.length) return null;

        return { labels, values };
    }

    function readInitialDataFromRoot(rootEl) {
        if (!rootEl) return null;

        const raw = rootEl.getAttribute('data-chart-initial-data');
        if (!raw) return null;

        try {
            return JSON.parse(raw);
        } catch (e) {
            console.warn(
                'Revenue trend chart: invalid data-chart-initial-data JSON.',
                e
            );
            return null;
        }
    }

    // Keeps track of chart instances (same architecture as your bar charts)
    const createChartRegistry = new Map();

    function createChartForRoot(rootEl) {
        if (typeof Chart === 'undefined') {
            console.warn('Chart.js not found.');
            return;
        }

        const chartId =
            rootEl.getAttribute('data-chart-id') ||
            'revenueTrendChart';

        const canvas = document.getElementById(chartId);

        if (!canvas) return;

        const sourceData = readInitialDataFromRoot(rootEl);
        const built = buildChartData(sourceData);

        if (!built) {
            console.warn(
                'Revenue trend chart data missing or malformed.'
            );
            return;
        }

        const { labels, values } = built;

        const existing = createChartRegistry.get(chartId);

        if (existing && typeof existing.destroy === 'function') {
            existing.destroy();
        }

        const ctx = canvas.getContext('2d');

        // Blue gradient fill under the line
        const gradient = ctx.createLinearGradient(
            0,
            0,
            0,
            canvas.height
        );

        gradient.addColorStop(0, 'rgba(37,99,235,0.25)');
        gradient.addColorStop(1, 'rgba(37,99,235,0.02)');

        const chart = new Chart(ctx, {
            type: 'line',

            data: {
                labels,

                datasets: [
                    {
                        label: 'Revenue',

                        data: values,

                        borderColor: '#2563EB',

                        backgroundColor: gradient,

                        fill: true,

                        borderWidth: 3,

                        tension: 0.4,

                        pointRadius: 0,

                        pointHoverRadius: 7,

                        pointBackgroundColor: '#2563EB',

                        pointBorderColor: '#ffffff',

                        pointBorderWidth: 3,

                        hitRadius: 18,

                        hoverRadius: 8,
                    },
                ],
            },

            options: {
                responsive: true,

                maintainAspectRatio: false,

                interaction: {
                    intersect: false,
                    mode: 'index',
                },

                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart',
                },

                plugins: {
                    legend: {
                        display: false,
                    },

                    tooltip: {
                        enabled: true,

                        mode: 'index',

                        intersect: false,

                        displayColors: false,

                        backgroundColor: '#111827',

                        titleColor: '#ffffff',

                        bodyColor: '#ffffff',

                        padding: 12,

                        callbacks: {
                            label(context) {
                                return formatPHP(context.raw ?? 0);
                            },
                        },
                    },
                },

                scales: {
                    x: {
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

                    y: {
                        beginAtZero: false,

                        grid: {
                            color: 'rgba(15,23,42,0.08)',

                            drawBorder: false,
                        },

                        ticks: {
                            color: '#4b5563',

                            callback(value) {
                                return formatPHP(value);
                            },
                        },
                    },
                },

                layout: {
                    padding: {
                        top: 12,
                        right: 16,
                        bottom: 8,
                        left: 8,
                    },
                },

                elements: {
                    line: {
                        borderJoinStyle: 'round',
                        borderCapStyle: 'round',
                    },
                },
            },
        });

        createChartRegistry.set(chartId, chart);
    }

    function init() {
        const roots = document.querySelectorAll(
            '[data-component="revenue-trend"]'
        );

        roots.forEach((rootEl) => {
            const chartId =
                rootEl.getAttribute('data-chart-id') ||
                'revenueTrendChart';

            const canvas = document.getElementById(chartId);

            if (canvas && canvas.parentElement) {
                canvas.parentElement.style.minHeight = '420px';
            }

            createChartForRoot(rootEl);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();