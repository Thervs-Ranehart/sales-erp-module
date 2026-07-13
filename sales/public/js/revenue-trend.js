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
        if (!source) {
            return null;
        }

        if (source.labels && source.datasets) {
            return source;
        }

        if (source.labels && source.values) {
            return {
                labels: source.labels,
                datasets: [
                    {
                        label: 'Revenue',
                        data: source.values,
                        fill: false,
                        borderColor: '#2563EB',
                        backgroundColor: 'rgba(37, 99, 235, 0.12)',
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#2563EB',
                        pointBorderColor: '#ffffff',
                        borderWidth: 3,
                    },
                ],
            };
        }

        return null;
    }

    function createRevenueTrendChart() {
        if (typeof Chart === 'undefined') {
            console.warn('Chart.js not found. Include Chart.js to render the revenue trend chart.');
            return;
        }

        const chartId = window.revenueTrendChartId || 'revenueTrendChart';
        const canvas = document.getElementById(chartId);
        if (!canvas) {
            return;
        }

        const sourceData = window.revenueTrendData || null;
        const chartData = buildChartData(sourceData);
        if (!chartData || !Array.isArray(chartData.labels) || !Array.isArray(chartData.datasets)) {
            console.warn('Revenue trend chart data is missing or malformed.');
            return;
        }

        if (window.revenueTrendChart) {
            window.revenueTrendChart.destroy();
            window.revenueTrendChart = null;
        }

        window.revenueTrendChart = new Chart(canvas, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const value = context.parsed.y ?? context.raw ?? 0;
                                return formatPHP(value);
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
                        },
                    },
                    y: {
                        grid: {
                            color: 'rgba(15, 23, 42, 0.08)',
                            drawBorder: false,
                        },
                        ticks: {
                            callback: function (value) {
                                return formatPHP(value);
                            },
                            color: '#4b5563',
                        },
                    },
                },
                layout: {
                    padding: {
                        top: 10,
                        right: 10,
                        bottom: 10,
                        left: 10,
                    },
                },
            },
        });
    }

    function init() {
        const canvas = document.getElementById(window.revenueTrendChartId || 'revenueTrendChart');
        if (canvas && canvas.parentElement) {
            canvas.parentElement.style.minHeight = '500px';
        }
        createRevenueTrendChart();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
