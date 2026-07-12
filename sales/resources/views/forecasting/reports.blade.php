@extends('layouts.app')

@section('content')
    @php($title = 'Sales Reports')
    @php($subtitle = 'Review performance and generate actionable insights')

    @php($tabs = [
        ['label' => 'Sales Reports', 'route' => 'forecasting.reports'],
        ['label' => 'Target vs. Actual', 'route' => 'forecasting.performance'],
        ['label' => 'Forecasting', 'route' => 'forecasting.forecast'],
        ['label' => 'Recommendations', 'route' => 'forecasting.recommendations'],
    ])

    @php($kpis = [
        ['label' => 'Total Revenue', 'value' => '₱4.8M', 'icon' => 'currency-dollar', 'tone' => 'text-success', 'accent' => 'rgba(22, 200, 199, 0.12)'],
        ['label' => 'Total Orders', 'value' => '1,248', 'icon' => 'cart-check', 'tone' => 'text-primary', 'accent' => 'rgba(83, 71, 206, 0.12)'],
        ['label' => 'Avg. Order Value', 'value' => '₱3,850', 'icon' => 'graph-up', 'tone' => 'text-warning', 'accent' => 'rgba(245, 158, 11, 0.14)'],
        ['label' => 'Active Customers', 'value' => '342', 'icon' => 'people-fill', 'tone' => 'text-info', 'accent' => 'rgba(72, 150, 254, 0.14)'],
        ['label' => 'Sales Growth', 'value' => '+12.4%', 'icon' => 'bar-chart-line', 'tone' => 'text-danger', 'accent' => 'rgba(239, 68, 68, 0.13)'],
        ['label' => 'Target Achievement', 'value' => '94%', 'icon' => 'bullseye', 'tone' => 'text-success', 'accent' => 'rgba(16, 185, 129, 0.14)'],
    ])

    <main class="w-full flex items-center" style="margin: -1rem -1rem 0; width: calc(100% + 2rem); padding: 40px 48px; height: 322px; max-height: 322px; background: linear-gradient(90deg, #128B99 0%, #1CE5BD 100%); border-radius: 0;">
        <div class="d-flex flex-column h-100 w-100">
            <div>
                <h1 class="text-white font-semibold text-[56px] leading-tight">Sales Reports</h1>
            </div>
            <div class="flex-grow-1 d-flex align-items-center">
                <p class="text-white" style="width: 100%; margin: 0; font-family: 'Poppins', sans-serif; font-size: 22px; font-weight: 600; line-height: 1.6; opacity: 0.95;">
                    Monitor sales performance, identify trends, and generate business insights.
                    Analyze revenue, product performance, regional sales, and employee achievements.
                </p>
            </div>
            <div>
                <a
                    href="{{ route('forecasting.reports') }}"
                    class="hero-action-btn"
                >
                    View Reports
                </a>
            </div>
        </div>
    </main>

    <section class="mt-4">
        <div class="row g-4">
            @foreach ($kpis as $kpi)
                <div class="col-md-6 col-lg-4">
                    <div class="card p-4 h-100 border-0 shadow-sm" style="background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%); min-height: 140px;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-semibold small text-muted">{{ $kpi['label'] }}</span>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: {{ $kpi['accent'] }};">
                                <i class="bi bi-{{ $kpi['icon'] }} fs-5 {{ $kpi['tone'] }}"></i>
                            </div>
                        </div>
                        <div class="fw-bold fs-3 {{ $kpi['tone'] }}">{{ $kpi['value'] }}</div>
                        <p class="text-muted small mb-0 mt-2">Updated from live sales data</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mt-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex gap-2 flex-wrap">
                @foreach ($tabs as $tab)
                    @php($isActive = Route::currentRouteName() === $tab['route'])
                    <a href="{{ route($tab['route']) }}" class="btn btn-sm {{ $isActive ? 'btn-primary' : 'btn-outline-secondary' }}">
                        {{ $tab['label'] }}
                    </a>
                @endforeach
            </div>

            <button id="rf-open-filter" class="btn btn-outline-secondary btn-sm" type="button">
                <i class="bi bi-funnel me-2"></i>
                Filter
            </button>

            <!-- Filter Drawer (Report Filters) -->
            <div id="rf-filter-overlay" class="rf-overlay" aria-hidden="true">
                <aside
                    id="rf-filter-drawer"
                    class="rf-drawer"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="rf-filter-title"
                >
                    <div class="rf-drawer-header">
                        <div>
                            <div class="rf-filter-icon">
                                <i class="bi bi-funnel"></i>
                            </div>
                        </div>

                        <div class="rf-drawer-header-text">
                            <h2 id="rf-filter-title" class="rf-drawer-title">Report Filters</h2>
                            <p class="rf-drawer-desc">Customize sales reports by selecting specific data parameters.</p>
                        </div>

                        <button id="rf-close-filter" class="rf-close" type="button" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <div class="rf-drawer-body">
                        <div class="rf-field">
                            <label class="rf-label" for="rf-date-range">Date Range</label>
                            <select id="rf-date-range" class="rf-select">
                                <option>Last 30 days</option>
                                <option>Last 90 days</option>
                                <option>This year</option>
                            </select>
                        </div>

                        <div class="rf-field">
                            <label class="rf-label" for="rf-region">Region</label>
                            <select id="rf-region" class="rf-select">
                                <option>All regions</option>
                                <option>National Capital Region (NCR)</option>
                                <option>Visayas</option>
                                <option>Mindanao</option>
                            </select>
                        </div>

                        <div class="rf-field">
                            <label class="rf-label" for="rf-status">Report Type</label>
                            <select id="rf-status" class="rf-select">
                                <option>Sales Reports</option>
                                <option>Target vs. Actual</option>
                                <option>Forecasting</option>
                                <option>Recommendations</option>
                            </select>
                        </div>

                        <div class="rf-actions">
                            <button type="button" class="rf-btn-secondary" id="rf-reset">Reset</button>
                            <button type="button" class="rf-btn-primary" id="rf-apply">Apply</button>
                        </div>
                    </div>
                </aside>
            </div>

            <style>
                .rf-overlay {
                    position: fixed;
                    inset: 0;
                    background: rgba(15, 23, 42, 0.45);
                    display: flex;
                    justify-content: flex-end;
                    z-index: 1050;
                    opacity: 0;
                    pointer-events: none;
                    transition: opacity 300ms ease;
                }

                .rf-overlay.open {
                    opacity: 1;
                    pointer-events: auto;
                }

                .rf-drawer {
                    width: min(420px, calc(100vw - 24px));
                    height: calc(100vh - 32px);
                    margin: 16px;
                    background: #ffffff;
                    border-radius: 18px;
                    box-shadow: 0 18px 45px rgba(0,0,0,0.22);
                    transform: translateX(110%);
                    transition: transform 380ms cubic-bezier(0.22, 1, 0.36, 1);
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                }

                .rf-overlay.open .rf-drawer {
                    transform: translateX(0);
                }

                .rf-drawer-header {
                    display: flex;
                    gap: 14px;
                    align-items: flex-start;
                    padding: 18px 18px 12px 18px;
                    border-bottom: 1px solid rgba(17, 24, 39, 0.08);
                }

                .rf-filter-icon {
                    width: 42px;
                    height: 42px;
                    border-radius: 14px;
                    background: rgba(83, 71, 206, 0.12);
                    color: #5347CE;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                    margin-top: 2px;
                }

                .rf-drawer-header-text {
                    flex: 1;
                    min-width: 0;
                }

                .rf-drawer-title {
                    font-size: 18px;
                    font-weight: 800;
                    margin: 0;
                    color: #0f172a;
                }

                .rf-drawer-desc {
                    margin: 6px 0 0 0;
                    font-size: 13px;
                    line-height: 1.35;
                    color: rgba(15, 23, 42, 0.7);
                }

                .rf-close {
                    width: 38px;
                    height: 38px;
                    border-radius: 12px;
                    border: 1px solid rgba(15, 23, 42, 0.12);
                    background: #fff;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: rgba(15, 23, 42, 0.8);
                    cursor: pointer;
                    flex-shrink: 0;
                }

                .rf-close:hover {
                    background: rgba(15, 23, 42, 0.04);
                }

                .rf-drawer-body {
                    padding: 14px 18px 18px 18px;
                    overflow: auto;
                }

                .rf-field {
                    margin-bottom: 14px;
                }

                .rf-label {
                    display: block;
                    font-size: 12px;
                    font-weight: 700;
                    color: rgba(15, 23, 42, 0.75);
                    margin-bottom: 8px;
                }

                .rf-select {
                    width: 100%;
                    height: 42px;
                    border-radius: 14px;
                    border: 1px solid rgba(15, 23, 42, 0.12);
                    background: #fff;
                    padding: 0 14px;
                    outline: none;
                    color: rgba(15, 23, 42, 0.95);
                }

                .rf-actions {
                    display: flex;
                    gap: 10px;
                    justify-content: flex-end;
                    margin-top: 10px;
                }

                .rf-btn-primary,
                .rf-btn-secondary {
                    border: 0;
                    height: 40px;
                    padding: 0 14px;
                    border-radius: 14px;
                    font-weight: 800;
                    cursor: pointer;
                    transition: transform 120ms ease, background 160ms ease;
                }

                .rf-btn-primary {
                    background: #5347CE;
                    color: #fff;
                }

                .rf-btn-primary:hover {
                    background: #4a3fd0;
                    transform: translateY(-1px);
                }

                .rf-btn-secondary {
                    background: rgba(83, 71, 206, 0.10);
                    color: #5347CE;
                    border: 1px solid rgba(83, 71, 206, 0.18);
                }

                .rf-btn-secondary:hover {
                    background: rgba(83, 71, 206, 0.14);
                    transform: translateY(-1px);
                }
            </style>

            <script>
                (function () {
                    const openBtn = document.getElementById('rf-open-filter');
                    const overlay = document.getElementById('rf-filter-overlay');
                    const drawer = document.getElementById('rf-filter-drawer');
                    const closeBtn = document.getElementById('rf-close-filter');
                    const resetBtn = document.getElementById('rf-reset');
                    const applyBtn = document.getElementById('rf-apply');

                    if (!openBtn || !overlay || !drawer || !closeBtn) return;

                    const open = () => overlay.classList.add('open');
                    const close = () => overlay.classList.remove('open');

                    openBtn.addEventListener('click', function () {
                        open();
                    });

                    closeBtn.addEventListener('click', function () {
                        close();
                    });

                    overlay.addEventListener('click', function (e) {
                        if (e.target === overlay) close();
                    });

                    document.addEventListener('keydown', function (e) {
                        if (e.key === 'Escape') close();
                    });

                    if (resetBtn) {
                        resetBtn.addEventListener('click', function () {
                            const selects = drawer.querySelectorAll('select');
                            selects.forEach(function (s) {
                                s.selectedIndex = 0;
                            });
                        });
                    }

                    if (applyBtn) {
                        applyBtn.addEventListener('click', function () {
                            // Placeholder: hook to your backend later.
                            close();
                        });
                    }
                })();
            </script>
        </div>
    </section>
@endsection

