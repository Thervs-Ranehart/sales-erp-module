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

        const labels = Array.isArray(source.labels)
            ? source.labels
            : null;

        const target = Array.isArray(source.target)
            ? source.target
            : null;

        const actual = Array.isArray(source.actual)
            ? source.actual
            : null;

        if (!labels || !target || !actual) {
            return null;
        }

        if (
            labels.length !== target.length ||
            labels.length !== actual.length
        ) {
            return null;
        }

        return {
            labels,
            target,
            actual,
        };
    }

    function readInitialDataFromRoot(rootEl) {
        if (!rootEl) return null;

        const raw = rootEl.getAttribute(
            'data-chart-initial-data'
        );

        if (!raw) return null;

        try {
            return JSON.parse(raw);
        } catch (e) {
            console.warn(
                'Target vs Actual chart: invalid data-chart-initial-data JSON.',
                e
            );

            return null;
        }
    }

    // Keeps track of chart instances
    const createChartRegistry = new Map();

    function createChartForRoot(rootEl) {
        if (typeof Chart === 'undefined') {
            console.warn('Chart.js not found.');
            return;
        }

        const chartId =
            rootEl.getAttribute('data-chart-id') ||
            'targetVsActualChart';

        const canvas = document.getElementById(chartId);

        if (!canvas) return;

        const sourceData = readInitialDataFromRoot(rootEl);

        const built = buildChartData(sourceData);

        if (!built) {
            console.warn(
                'Target vs Actual chart data missing or malformed.'
            );
            return;
        }

        const {
            labels,
            target,
            actual,
        } = built;

        const existing =
            createChartRegistry.get(chartId);

        if (
            existing &&
            typeof existing.destroy === 'function'
        ) {
            existing.destroy();
        }

        const ctx = canvas.getContext('2d');

        // Gradient for Actual Revenue
        const actualGradient =
            ctx.createLinearGradient(
                0,
                0,
                0,
                canvas.height
            );

        actualGradient.addColorStop(
            0,
            'rgba(37,99,235,0.25)'
        );

        actualGradient.addColorStop(
            1,
            'rgba(37,99,235,0.02)'
        );

        const chart = new Chart(ctx, {
            type: 'line',

            data: {
                labels,

                datasets: [
                    {
                        label: 'Actual Revenue',

                        data: actual,

                        borderColor: '#2563EB',

                        backgroundColor:
                            actualGradient,

                        fill: true,

                        borderWidth: 3,

                        tension: 0.4,

                        pointRadius: 0,

                        pointHoverRadius: 7,

                        pointBackgroundColor:
                            '#2563EB',

                        pointBorderColor:
                            '#ffffff',

                        pointBorderWidth: 3,

                        hitRadius: 18,

                        hoverRadius: 8,
                    },

                    {
                        label: 'Sales Target',

                        data: target,

                        borderColor: '#10B981',

                        backgroundColor:
                            'transparent',

                        borderDash: [8, 6],

                        fill: false,

                        borderWidth: 3,

                        tension: 0.4,

                        pointRadius: 0,

                        pointHoverRadius: 7,

                        pointBackgroundColor:
                            '#10B981',

                        pointBorderColor:
                            '#ffffff',

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
                        display: true,

                        position: 'top',

                        align: 'end',

                        labels: {
                            usePointStyle: true,

                            pointStyle: 'line',

                            padding: 20,

                            color: '#374151',

                            font: {
                                size: 12,
                                weight: '600',
                            },
                        },
                    },

                    tooltip: {
                        enabled: true,

                        mode: 'index',

                        intersect: false,

                        backgroundColor: '#111827',

                        titleColor: '#ffffff',

                        bodyColor: '#ffffff',

                        padding: 12,

                        callbacks: {
                            label(context) {
                                return `${context.dataset.label}: ${formatPHP(
                                    context.raw ?? 0
                                )}`;
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
            '[data-component="target-vs-actual-chart"]'
        );

        roots.forEach((rootEl) => {
            const chartId =
                rootEl.getAttribute('data-chart-id') ||
                'targetVsActualChart';

            const canvas = document.getElementById(chartId);

            if (canvas && canvas.parentElement) {
                canvas.parentElement.style.minHeight = '420px';
            }

            createChartForRoot(rootEl);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener(
            'DOMContentLoaded',
            init
        );
    } else {
        init();
    }
})();