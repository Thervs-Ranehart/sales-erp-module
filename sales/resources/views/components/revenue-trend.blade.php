@php
    // Allow optional override of the chart element id when including this component
    $chartId = $chartId ?? 'revenueTrendChart';
    // Default months (January - December) used as placeholder labels.
    $defaultMonths = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    // Placeholder dataset (temporary). Replace via backend injection (see comments below).
    $placeholderData = [1200000, 950000, 1100000, 1250000, 1400000, 1300000, 1500000, 1600000, 1550000, 1700000, 1800000, 1900000];

    // Optional initial data that a controller or parent view may pass when including this component.
    // Example: include this component and pass an array named `initialData` from your controller
    // (the array should follow Chart.js structure: { labels: [...], datasets: [...] }).
    $initialData = $initialData ?? null;
    // JSON encode safely for insertion into inline JS. If null, it will render `null` in JS.
    $initialDataJson = json_encode($initialData);
@endphp

<section aria-labelledby="revenue-trend-title" class="mt-4">
    <div class="max-w-full">
        <div class="flex items-center justify-between mb-3">
            <h3 id="revenue-trend-title" class="text-lg font-bold text-gray-900">Revenue Trend (Monthly Revenue)</h3>
            <!-- future: year selector / export buttons go here -->
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="w-full">
                <!-- Chart canvas. The id can be overridden by passing $chartId when including this component. -->
                <canvas id="{{ $chartId }}" aria-label="Monthly revenue trend chart" role="img"></canvas>
            </div>
        </div>
    </div>

    {{--
        JavaScript data and initialization.
        Architecture notes:
                - Do NOT hardcode business logic here. The `revenueTrendData` object is the single source
                    of truth for the chart and can be replaced with any data source later:
                        * Blade:      Pass a PHP variable from your controller (JSON-serializable) and inject it here.
            * Livewire:   use `@this` or emit events to update `revenueTrendData` in JS
            * API/AJAX:   fetch('/api/revenue').then(r => r.json()).then(d => updateChart(d))
        - The chart initialization reads `revenueTrendData` and does not assume a backend format
          beyond `labels` and `datasets` matching Chart.js expectations.
    --}}

    <script>
        (function () {
            // Ensure Chart.js is available. If not, caller should include it (CDN or bundled).
            if (typeof Chart === 'undefined') {
                console.warn('Chart.js not found. Include Chart.js to render the revenue trend chart.');
                return;
            }

            // Replace these placeholders with backend data when ready.
            // You can pass initial data when including this component from your controller/view
            // (the data must be an object with `labels` and `datasets` matching Chart.js),
            // or change data later from JS using `window.updateRevenueTrendData(newData)`.

            const backendData = {!! $initialDataJson !!};

            const revenueTrendData = (backendData && backendData.labels && backendData.datasets)
                ? backendData
                : {
                    labels: {!! json_encode($defaultMonths) !!},
                    datasets: [
                        {
                            label: 'Revenue',
                            data: {!! json_encode($placeholderData) !!},
                            fill: false,
                            borderColor: '#2563EB', // blue
                            backgroundColor: 'rgba(37,99,235,0.08)',
                            tension: 0.35, // smooth curve; make configurable later
                            pointRadius: 3,
                            pointBackgroundColor: '#2563EB',
                            pointBorderColor: '#fff',
                            borderWidth: 2,
                        }
                    ]
                };

            // Chart instance holder so external code can update it later.
            let revenueTrendChart = null;

            function formatPHP(value) {
                try {
                    return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP', maximumFractionDigits: 0 }).format(value);
                } catch (e) {
                    // Fallback formatting
                    return '₱' + Number(value).toLocaleString();
                }
            }

            function createChart() {
                const ctx = document.getElementById('{{ $chartId }}');
                if (!ctx) return null;

                // Destroy previous instance if re-initializing
                if (revenueTrendChart) {
                    revenueTrendChart.destroy();
                    revenueTrendChart = null;
                }

                revenueTrendChart = new Chart(ctx, {
                    type: 'line',
                    data: revenueTrendData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const v = context.parsed.y ?? context.raw ?? 0;
                                        return formatPHP(v);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6b7280'
                                }
                            },
                            y: {
                                grid: {
                                    color: 'rgba(15, 23, 42, 0.06)',
                                    drawBorder: false
                                },
                                ticks: {
                                    callback: function (value) { return formatPHP(value); },
                                    color: '#6b7280'
                                }
                            }
                        },
                        layout: {
                            padding: { top: 6, right: 6, bottom: 6, left: 6 }
                        }
                    }
                });

                // Expose update function for future dynamic data updates
                return revenueTrendChart;
            }

            // Create a responsive container height to give Chart.js some room
            (function setCanvasHeight() {
                const canvas = document.getElementById('{{ $chartId }}');
                if (!canvas) return;
                const parent = canvas.parentElement;
                if (parent) parent.style.minHeight = '220px';
            })();

            // Initialize chart when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', createChart);
            } else {
                createChart();
            }

            // Provide a global accessor to update the chart later without changing init code.
            // Usage example from other scripts: window.updateRevenueTrendData(newDataObject);
            window.updateRevenueTrendData = function (newData) {
                if (!revenueTrendChart) createChart();
                if (!revenueTrendChart) return;
                // Expect newData to have { labels: [], datasets: [] }
                if (newData.labels) revenueTrendChart.data.labels = newData.labels;
                if (newData.datasets) revenueTrendChart.data.datasets = newData.datasets;
                revenueTrendChart.update();
            };
        })();
    </script>
</section>
