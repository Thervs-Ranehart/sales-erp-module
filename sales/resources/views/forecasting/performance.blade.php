@extends('layouts.app')

@section('content')
    @php
        $title = 'Target vs. Actual Performance';

        $subtitle = 'Compare planned sales targets with actual business performance to evaluate achievement, identify performance gaps, and monitor progress.';

        $tabs = [
            [
                'label' => 'Sales Reports',
                'route' => 'forecasting.reports',
            ],
            [
                'label' => 'Target vs. Actual',
                'route' => 'forecasting.performance',
            ],
            [
                'label' => 'Forecasting',
                'route' => 'forecasting.forecast',
            ],
            [
                'label' => 'Recommendations',
                'route' => 'forecasting.recommendations',
            ],
        ];

        /*
         * Transform the KPI data coming from ForecastingController
         * into the format required by the KPI cards.
         */
        $performanceKpis = [
            [
                'label' => 'Sales Target',
                'value' => '₱' . number_format($kpis['salesTargetTotal'] ?? 0),
                'icon' => 'bullseye',
                'tone' => 'text-primary',
                'accent' => 'rgba(83, 71, 206, 0.12)',
            ],
            [
                'label' => 'Actual Revenue',
                'value' => '₱' . number_format($kpis['actualRevenueTotal'] ?? 0),
                'icon' => 'cash-stack',
                'tone' => 'text-success',
                'accent' => 'rgba(16, 185, 129, 0.14)',
            ],
            [
                'label' => 'Achievement Rate',
                'value' => number_format($kpis['achievementRate'] ?? 0) . '%',
                'icon' => 'graph-up-arrow',
                'tone' => ($kpis['achievementRate'] ?? 0) >= 100
                    ? 'text-success'
                    : 'text-primary',
                'accent' => 'rgba(37, 99, 235, 0.12)',
            ],
            [
                'label' => 'Sales Gap',
                'value' => (($kpis['salesGap'] ?? 0) >= 0 ? '+' : '-')
                    . '₱'
                    . number_format(abs($kpis['salesGap'] ?? 0)),
                'icon' => 'arrow-left-right',
                'tone' => ($kpis['salesGap'] ?? 0) >= 0
                    ? 'text-success'
                    : 'text-warning',
                'accent' => ($kpis['salesGap'] ?? 0) >= 0
                    ? 'rgba(16, 185, 129, 0.14)'
                    : 'rgba(245, 158, 11, 0.14)',
            ],
            [
                'label' => 'Best Performer',
                'value' => ($kpis['bestPerformer']['name'] ?? 'N/A')
                    . ' '
                    . ($kpis['bestPerformer']['achievement'] ?? 0)
                    . '%',
                'icon' => 'trophy',
                'tone' => 'text-success',
                'accent' => 'rgba(16, 185, 129, 0.14)',
            ],
            [
                'label' => 'Lowest Performer',
                'value' => ($kpis['lowestPerformer']['name'] ?? 'N/A')
                    . ' '
                    . ($kpis['lowestPerformer']['achievement'] ?? 0)
                    . '%',
                'icon' => 'exclamation-triangle',
                'tone' => 'text-danger',
                'accent' => 'rgba(239, 68, 68, 0.13)',
            ],
        ];
    @endphp

    {{-- Page Jumbotron --}}
    <main
        class="w-full flex items-center"
        style="
            margin: -1rem -1rem 0;
            width: calc(100% + 2rem);
            padding: 32px 48px;
            height: 258px;
            max-height: 258px;
            background: linear-gradient(90deg, #128B99 0%, #1CE5BD 100%);
            border-radius: 0;
        "
    >
        <div class="d-flex flex-column h-100 w-100">
            <div>
                <h1 class="text-white font-semibold text-[45px] leading-tight">
                    {{ $title }}
                </h1>
            </div>

            <div class="flex-grow-1 d-flex align-items-center">
                <p
                    class="text-white"
                    style="
                        width: 100%;
                        margin: 0;
                        font-family: 'Poppins', sans-serif;
                        font-size: 18px;
                        font-weight: 600;
                        line-height: 1.6;
                        opacity: 0.95;
                    "
                >
                    {{ $subtitle }}
                </p>
            </div>

            <div>
                <a
                    href="{{ route('forecasting.export', array_merge(['type'=>'performance','format'=>'csv'], request()->query())) }}"
                    class="hero-action-btn"
                    style="padding: 0.75rem 1.6rem;"
                >
                    <i class="bi bi-download me-2"></i>
                    Export Performance Data
                </a>
                <a href="{{ route('forecasting.export', array_merge(['type'=>'performance','format'=>'print'], request()->query())) }}" target="_blank" class="hero-secondary-btn ms-2"><i class="bi bi-printer me-2"></i>Print / PDF</a>
            </div>
        </div>
    </main>

    {{-- KPI Cards --}}
    <section class="mt-4" aria-label="Target versus actual KPI summary">
        <div class="row g-4">
            @foreach ($performanceKpis as $performanceKpi)
                <div class="col-md-6 col-lg-4">
                    <article
                        class="card px-3 py-2 h-100 border-0 shadow-sm"
                        style="
                            background: linear-gradient(
                                145deg,
                                #ffffff 0%,
                                #f8fafc 100%
                            );
                            min-height: 92px;
                        "
                    >
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fw-semibold small text-muted">
                                {{ $performanceKpi['label'] }}
                            </span>

                            <div
                                class="rounded-circle d-flex align-items-center justify-content-center"
                                style="
                                    width: 32px;
                                    height: 32px;
                                    background: {{ $performanceKpi['accent'] }};
                                "
                            >
                                <i
                                    class="bi bi-{{ $performanceKpi['icon'] }} fs-6 {{ $performanceKpi['tone'] }}"
                                    aria-hidden="true"
                                ></i>
                            </div>
                        </div>

                        <div class="fw-bold fs-4 {{ $performanceKpi['tone'] }}">
                            {{ $performanceKpi['value'] }}
                        </div>

                        <p class="text-muted small mb-0 mt-1">
                            Performance snapshot
                        </p>
                    </article>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Submenu Tab Bar and Filter Button --}}
    <section class="mt-4" aria-label="Sales performance navigation">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <nav
                class="d-flex gap-2 flex-wrap"
                aria-label="Sales performance reporting tabs"
            >
                @foreach ($tabs as $tab)
                    @php
                        $isActive = Route::currentRouteName() === $tab['route'];
                    @endphp

                    <a
                        href="{{ route($tab['route']) }}"
                        class="btn btn-sm {{ $isActive ? 'btn-primary' : 'btn-outline-secondary' }}"
                        @if ($isActive) aria-current="page" @endif
                    >
                        {{ $tab['label'] }}
                    </a>
                @endforeach
            </nav>

            <button
                id="rf-open-filter"
                type="button"
                class="btn btn-outline-secondary btn-sm"
                aria-label="Open performance filters"
                aria-controls="rf-filter-drawer"
                aria-expanded="false"
            >
                <i class="bi bi-funnel me-2" aria-hidden="true"></i>
                Filter
            </button>
        </div>
    </section>

    {{-- Target vs. Actual Revenue Chart --}}
    <section class="mt-5" aria-label="Monthly target versus actual revenue">
        <x-target-vs-actual-chart
            :initial-data="$monthlyTargetVsActual"
            chart-id="targetVsActualChart"
        />
    </section>

    <section class="mt-5" aria-labelledby="achievement-trend-heading">
        <x-performance-percentage-chart
            :initial-data="$achievementTrend"
            chart-id="achievementTrendChart"
            title="Achievement Rate Trend"
            type="line"
            description="100% means the sales target was achieved."
        />
    </section>

    <section class="mt-5" aria-labelledby="performance-breakdown-heading">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-3">
            <div>
                <h2 id="performance-breakdown-heading" class="fs-5 fw-bold text-gray-900 mb-1">Performance Breakdown</h2>
                <p class="small text-muted mb-0">Compare achievement across products, regions, and representatives.</p>
            </div>
            <span class="badge rounded-pill text-bg-light border px-3 py-2">
                <i class="bi bi-activity me-1" aria-hidden="true"></i>
                Achievement overview
            </span>
        </div>
        <div class="row g-4">
            <div class="col-12 col-xl-4">
                <x-performance-percentage-chart :initial-data="$achievementByProduct" chart-id="achievementByProductChart" title="Achievement by Product" description="See which product groups are driving results." icon="box-seam" />
            </div>
            <div class="col-12 col-xl-4">
                <x-performance-percentage-chart :initial-data="$achievementByRegion" chart-id="achievementByRegionChart" title="Achievement by Region" description="Review target attainment across sales territories." icon="geo-alt" />
            </div>
            <div class="col-12 col-xl-4">
                <x-performance-percentage-chart :initial-data="$achievementByRepresentative" chart-id="achievementByRepresentativeChart" title="Achievement by Representative" description="Identify individual progress and top performers." icon="people" />
            </div>
        </div>
    </section>

    <section class="mt-5" aria-labelledby="performance-table-heading">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 px-3 px-md-4 pt-4 pb-2">
                <h2 id="performance-table-heading" class="fs-5 fw-bold mb-0">Detailed Performance</h2>
            </div>
            <div class="card-body px-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 ps-md-4">Sales Representative</th>
                                <th>Target</th><th>Actual</th><th>Achievement</th><th>Gap</th><th class="pe-3 pe-md-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($targetVsActualRows as $row)
                                @php
                                    $statusClass = match ($row['status']) {
                                        'Exceeded' => 'text-bg-success',
                                        'On Target' => 'text-bg-primary',
                                        default => 'text-bg-danger',
                                    };
                                @endphp
                                <tr>
                                    <th scope="row" class="ps-3 ps-md-4 text-nowrap">{{ $row['employee_name'] }}</th>
                                    <td class="text-nowrap">₱{{ number_format($row['target']) }}</td>
                                    <td class="text-nowrap">₱{{ number_format($row['actual']) }}</td>
                                    <td class="fw-semibold {{ $row['achievement'] >= 100 ? 'text-success' : 'text-danger' }}">{{ number_format($row['achievement']) }}%</td>
                                    <td class="text-nowrap fw-semibold {{ $row['gap'] >= 0 ? 'text-success' : 'text-danger' }}">{{ $row['gap'] >= 0 ? '+' : '-' }}₱{{ number_format(abs($row['gap'])) }}</td>
                                    <td class="pe-3 pe-md-4"><span class="badge rounded-pill {{ $statusClass }}">{{ $row['status'] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-5" aria-labelledby="performance-insights-heading">
        <h2 id="performance-insights-heading" class="text-lg font-bold text-gray-900 mb-3">Performance Insights</h2>
        <div class="row g-3">
            @foreach ($performanceInsights as $insight)
                <div class="col-12 col-md-6">
                    <div class="alert {{ $insight['type'] === 'success' ? 'alert-success' : 'alert-warning' }} d-flex align-items-start gap-3 h-100 mb-0" role="status">
                        <i class="bi {{ $insight['type'] === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' }} mt-1" aria-hidden="true"></i>
                        <span>{{ $insight['text'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mt-5 mb-4" aria-labelledby="sales-targets-heading">
        <div class="target-manager">
            <header class="target-manager__header">
                <div class="target-manager__heading">
                    <span class="target-manager__icon" aria-hidden="true">
                        <i class="bi bi-bullseye"></i>
                    </span>
                    <div>
                        <h2 id="sales-targets-heading">Manage Sales Targets</h2>
                        <p>Create monthly goals and keep your team aligned with revenue expectations.</p>
                    </div>
                </div>
                <span class="target-manager__count">
                    <i class="bi bi-list-check" aria-hidden="true"></i>
                    {{ number_format($salesTargets->count()) }}
                    {{ $salesTargets->count() === 1 ? 'target' : 'targets' }}
                </span>
            </header>

            <div class="target-manager__body">
                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center gap-2 rounded-3" role="status">
                        <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger rounded-3" role="alert">
                        <div class="d-flex align-items-center gap-2 fw-semibold mb-1">
                            <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
                            Please review the target details.
                        </div>
                        <ul class="mb-0 ps-4">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('forecasting.targets.store') }}" class="target-form">
                    @csrf
                    <div class="target-form__intro">
                        <h3>Target details</h3>
                        <p>Select a representative and define their goals for a specific reporting period.</p>
                    </div>
                    <div class="target-form__grid">
                        <div class="target-field target-field--representative">
                            <label for="target-employee">Sales representative</label>
                            <div class="target-control">
                                <i class="bi bi-person" aria-hidden="true"></i>
                                <select id="target-employee" name="employee_id" required>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->employee_id }}" @selected((string) old('employee_id') === (string) $employee->employee_id)>
                                            {{ $employee->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="target-field">
                            <label for="target-month">Month</label>
                            <div class="target-control">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                <select id="target-month" name="target_month" required>
                                    @foreach(range(1, 12) as $month)
                                        <option value="{{ $month }}" @selected((int) old('target_month', now()->month) === $month)>
                                            {{ now()->setMonth($month)->format('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="target-field">
                            <label for="target-year">Year</label>
                            <div class="target-control">
                                <i class="bi bi-calendar-event" aria-hidden="true"></i>
                                <input id="target-year" name="target_year" type="number" min="2020" max="2100" value="{{ old('target_year', request('year', now()->year)) }}" required>
                            </div>
                        </div>
                        <div class="target-field">
                            <label for="sales-target">Order target</label>
                            <div class="target-control">
                                <i class="bi bi-bag-check" aria-hidden="true"></i>
                                <input id="sales-target" name="sales_target" type="number" min="0" value="{{ old('sales_target', 0) }}" required>
                            </div>
                        </div>
                        <div class="target-field">
                            <label for="revenue-target">Revenue target</label>
                            <div class="target-control">
                                <span aria-hidden="true">₱</span>
                                <input id="revenue-target" name="revenue_target" type="number" min="0" step="0.01" value="{{ old('revenue_target') }}" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>
                    <div class="target-form__footer">
                        <p><i class="bi bi-info-circle" aria-hidden="true"></i> An existing target with the same representative and period will be updated.</p>
                        <button class="target-save-button" type="submit">
                            <i class="bi bi-plus-lg" aria-hidden="true"></i>
                            Save sales target
                        </button>
                    </div>
                </form>

                <div class="target-records">
                    <div class="target-records__header">
                        <div>
                            <h3>Saved targets</h3>
                            <p>Current sales and revenue goals by representative.</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="target-table">
                            <thead>
                                <tr>
                                    <th>Representative</th>
                                    <th>Reporting period</th>
                                    <th>Order goal</th>
                                    <th>Revenue goal</th>
                                    <th><span class="visually-hidden">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salesTargets as $target)
                                    @php($representative = $target->employee?->full_name ?? 'No representative assigned')
                                    <tr>
                                        <td>
                                            <div class="target-representative">
                                                <span class="target-representative__avatar" aria-hidden="true">
                                                    {{ strtoupper(substr($representative, 0, 1)) }}
                                                </span>
                                                <div>
                                                    <strong>{{ $representative }}</strong>
                                                    <span>Sales representative</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="target-period">
                                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                                {{ now()->setMonth($target->target_month)->format('F') }} {{ $target->target_year }}
                                            </span>
                                        </td>
                                        <td><strong class="target-metric">{{ number_format($target->sales_target) }}</strong></td>
                                        <td><strong class="target-metric target-metric--revenue">₱{{ number_format($target->revenue_target, 2) }}</strong></td>
                                        <td class="target-table__action">
                                            <form id="delete-target-form-{{ $target->getKey() }}" method="POST" action="{{ route('forecasting.targets.destroy', $target) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    class="target-delete-button"
                                                    type="button"
                                                    data-delete-target
                                                    data-form-id="delete-target-form-{{ $target->getKey() }}"
                                                    data-representative="{{ $representative }}"
                                                    data-period="{{ now()->setMonth($target->target_month)->format('F') }} {{ $target->target_year }}"
                                                    data-orders="{{ number_format($target->sales_target) }}"
                                                    data-revenue="₱{{ number_format($target->revenue_target, 2) }}"
                                                    aria-label="Delete sales target for {{ $representative }}"
                                                    title="Delete target"
                                                >
                                                    <i class="bi bi-trash3" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="target-empty">
                                                <span><i class="bi bi-bullseye" aria-hidden="true"></i></span>
                                                <h4>No sales targets yet</h4>
                                                <p>Use the form above to create the first target for your team.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div
        id="target-delete-modal"
        class="sales-delete-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="target-delete-title"
        aria-describedby="target-delete-description"
        hidden
    >
        <div class="sales-delete-panel">
            <div class="sales-delete-icon" aria-hidden="true">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <h3 id="target-delete-title">Delete sales target?</h3>
            <p id="target-delete-description">Are you sure you want to delete this record? This action cannot be undone.</p>

            <div class="sales-delete-record">
                <div class="sales-delete-record-entry">
                    <strong id="target-delete-record-heading"></strong>
                    <dl>
                        <dt>Period</dt><dd id="target-delete-period"></dd>
                        <dt>Order target</dt><dd id="target-delete-orders"></dd>
                        <dt>Revenue target</dt><dd id="target-delete-revenue"></dd>
                    </dl>
                </div>
            </div>

            <p class="sales-delete-countdown" id="target-delete-countdown-message" aria-live="assertive" hidden>
                <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                <span>
                    Deletion is scheduled. You can cancel it before the record is deleted.
                    <strong><span id="target-delete-countdown">5</span> seconds remaining.</strong>
                </span>
            </p>

            <div class="sales-delete-actions">
                <button type="button" class="sales-delete-cancel" data-target-delete-cancel>Cancel</button>
                <button type="button" class="sales-delete-confirm" id="target-delete-confirm">
                    <i class="bi bi-trash3 me-1"></i>
                    Delete this record
                </button>
            </div>
        </div>
    </div>

    @include('components.report-filter', ['filterMode' => 'performance'])

    <style>
        .target-manager{overflow:hidden;border:1px solid #e2e8f0;border-radius:22px;background:#fff;box-shadow:0 16px 40px rgba(15,23,42,.07)}
        .target-manager__header{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:24px 28px;border-bottom:1px solid #e2e8f0;background:linear-gradient(135deg,#f8fafc 0%,#f0fdfa 100%)}
        .target-manager__heading{display:flex;align-items:center;gap:14px}
        .target-manager__icon{flex:0 0 auto;width:48px;height:48px;display:grid;place-items:center;border-radius:14px;color:#fff;background:linear-gradient(135deg,#128b99,#14b8a6);font-size:21px;box-shadow:0 9px 20px rgba(18,139,153,.22)}
        .target-manager__heading h2{margin:0 0 4px;color:#0f172a;font-size:20px;font-weight:750}
        .target-manager__heading p,.target-records__header p{margin:0;color:#64748b;font-size:13px}
        .target-manager__count{display:inline-flex;align-items:center;gap:7px;padding:8px 12px;border:1px solid #cbd5e1;border-radius:999px;color:#475569;background:rgba(255,255,255,.8);font-size:12px;font-weight:700;white-space:nowrap}
        .target-manager__body{padding:26px 28px 28px}
        .target-form{padding:22px;border:1px solid #e2e8f0;border-radius:16px;background:#f8fafc}
        .target-form__intro h3,.target-records__header h3{margin:0 0 3px;color:#1e293b;font-size:15px;font-weight:750}
        .target-form__intro p{margin:0 0 19px;color:#64748b;font-size:12px}
        .target-form__grid{display:grid;grid-template-columns:2fr repeat(4,1fr);gap:14px}
        .target-field label{display:block;margin:0 0 7px;color:#334155;font-size:12px;font-weight:700}
        .target-control{position:relative;display:flex;align-items:center}
        .target-control>i,.target-control>span{position:absolute;left:13px;z-index:1;color:#94a3b8;font-size:14px;pointer-events:none}
        .target-control input,.target-control select{width:100%;height:44px;padding:0 13px 0 38px;border:1px solid #cbd5e1;border-radius:10px;outline:0;color:#1e293b;background:#fff;font-size:13px;transition:border-color .18s ease,box-shadow .18s ease}
        .target-control select{appearance:auto}
        .target-control input:focus,.target-control select:focus{border-color:#14a3a5;box-shadow:0 0 0 3px rgba(20,163,165,.13)}
        .target-form__footer{display:flex;align-items:center;justify-content:space-between;gap:20px;margin-top:18px;padding-top:18px;border-top:1px solid #e2e8f0}
        .target-form__footer p{display:flex;align-items:center;gap:7px;margin:0;color:#64748b;font-size:11px}
        .target-form__footer p i{color:#128b99}
        .target-save-button{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:42px;padding:10px 17px;border:0;border-radius:10px;color:#fff;background:linear-gradient(135deg,#128b99,#0f9f8f);font-size:12px;font-weight:750;box-shadow:0 8px 18px rgba(18,139,153,.2);transition:transform .18s ease,box-shadow .18s ease}
        .target-save-button:hover,.target-save-button:focus-visible{transform:translateY(-1px);box-shadow:0 11px 22px rgba(18,139,153,.28)}
        .target-records{margin-top:26px;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden}
        .target-records__header{padding:18px 20px;border-bottom:1px solid #e2e8f0;background:#fff}
        .target-table{width:100%;border-collapse:collapse}
        .target-table th{padding:12px 18px;border-bottom:1px solid #e2e8f0;color:#64748b;background:#f8fafc;font-size:10px;font-weight:800;letter-spacing:.055em;text-align:left;text-transform:uppercase;white-space:nowrap}
        .target-table td{padding:15px 18px;border-bottom:1px solid #edf2f7;color:#334155;font-size:13px;vertical-align:middle}
        .target-table tbody tr:last-child td{border-bottom:0}
        .target-table tbody tr:not(:has(.target-empty)){transition:background .16s ease}
        .target-table tbody tr:not(:has(.target-empty)):hover{background:#f8fafc}
        .target-representative{display:flex;align-items:center;gap:11px;min-width:190px}
        .target-representative__avatar{flex:0 0 auto;width:36px;height:36px;display:grid;place-items:center;border:1px solid #ccfbf1;border-radius:11px;color:#0f766e;background:#f0fdfa;font-size:13px;font-weight:800}
        .target-representative strong{display:block;color:#1e293b;font-size:13px}
        .target-representative div span{display:block;margin-top:2px;color:#94a3b8;font-size:10px}
        .target-period{display:inline-flex;align-items:center;gap:7px;white-space:nowrap}
        .target-period i{color:#128b99}
        .target-metric{color:#334155;font-size:13px;font-variant-numeric:tabular-nums;white-space:nowrap}
        .target-metric--revenue{color:#0f766e}
        .target-table__action{width:66px;text-align:right}
        .target-table__action form{display:inline-block;margin:0}
        .target-delete-button{width:34px;height:34px;display:grid;place-items:center;border:1px solid #fecaca;border-radius:9px;color:#dc2626;background:#fff;font-size:13px;transition:color .18s ease,background .18s ease,transform .18s ease}
        .target-delete-button:hover,.target-delete-button:focus-visible{color:#fff;background:#dc2626;transform:translateY(-1px)}
        .target-empty{padding:34px 20px;text-align:center}
        .target-empty>span{width:50px;height:50px;display:grid;place-items:center;margin:0 auto 12px;border-radius:15px;color:#128b99;background:#ecfeff;font-size:21px}
        .target-empty h4{margin:0 0 5px;color:#334155;font-size:14px;font-weight:750}
        .target-empty p{margin:0;color:#94a3b8;font-size:12px}
        .sales-delete-modal{position:fixed;inset:0;z-index:2000;display:grid;place-items:center;padding:20px;background:rgba(15,23,42,.58);backdrop-filter:blur(5px);opacity:0;transition:opacity .22s ease}
        .sales-delete-modal[hidden]{display:none}
        .sales-delete-modal.is-open{opacity:1}
        .sales-delete-panel{width:min(100%,460px);padding:30px;border:1px solid #fee2e2;border-radius:22px;background:#fff;box-shadow:0 30px 70px rgba(15,23,42,.28);text-align:center;transform:translateY(18px) scale(.96);opacity:0;transition:transform .24s cubic-bezier(.22,1,.36,1),opacity .2s ease}
        .sales-delete-modal.is-open .sales-delete-panel{transform:translateY(0) scale(1);opacity:1}
        .sales-delete-icon{width:68px;height:68px;display:grid;place-items:center;margin:0 auto 18px;border:1px solid #fecaca;border-radius:20px;color:#dc2626;background:#fef2f2;font-size:30px;box-shadow:0 10px 24px rgba(220,38,38,.12)}
        .sales-delete-panel h3{margin:0 0 8px;color:#111827;font-size:22px;font-weight:700}
        .sales-delete-panel>p{margin:0;color:#64748b;font-size:13px;line-height:1.65}
        .sales-delete-record{margin:20px 0 22px;padding:5px 16px;border:1px solid #e5e7eb;border-radius:14px;background:#f8fafc;text-align:left}
        .sales-delete-record-entry{padding:13px 0}
        .sales-delete-record-entry strong{display:block;margin-bottom:9px;color:#1e293b;font-size:14px}
        .sales-delete-record-entry dl{display:grid;grid-template-columns:auto 1fr;gap:7px 14px;margin:0;font-size:11px}
        .sales-delete-record-entry dt{color:#64748b;font-weight:500}
        .sales-delete-record-entry dd{margin:0;overflow:hidden;color:#334155;font-weight:700;text-align:right;text-overflow:ellipsis;white-space:nowrap}
        .sales-delete-countdown{display:flex;align-items:center;gap:10px;margin:-6px 0 18px!important;padding:11px 13px;border:1px solid #fecaca;border-radius:11px;color:#991b1b!important;background:#fff7f7;font-size:14px!important;line-height:1.55!important;text-align:left}
        .sales-delete-countdown[hidden]{display:none}
        .sales-delete-countdown i{flex:0 0 auto;color:#dc2626;font-size:18px}
        .sales-delete-countdown strong{font-size:15px;font-weight:800;white-space:nowrap}
        .sales-delete-actions{display:flex;justify-content:center;gap:10px}
        .sales-delete-actions button{min-height:44px;padding:10px 18px;border-radius:11px;font-size:12px;font-weight:700;transition:transform .18s ease,box-shadow .18s ease,background .18s ease}
        .sales-delete-cancel{border:1px solid #dbe1ea;color:#475569;background:#fff}
        .sales-delete-cancel:hover,.sales-delete-cancel:focus-visible{color:#1e293b;background:#f1f5f9}
        .sales-delete-confirm{border:1px solid #dc2626;color:#fff;background:#dc2626;box-shadow:0 8px 18px rgba(220,38,38,.2)}
        .sales-delete-confirm:hover,.sales-delete-confirm:focus-visible{border-color:#b91c1c;background:#b91c1c;box-shadow:0 11px 22px rgba(185,28,28,.28);transform:translateY(-2px)}
        .sales-delete-confirm:disabled{cursor:wait;opacity:.82;transform:none}
        body.sales-delete-modal-open{overflow:hidden}
        @media(max-width:1100px){.target-form__grid{grid-template-columns:repeat(2,1fr)}.target-field--representative{grid-column:1/-1}}
        @media(max-width:680px){.target-manager__header,.target-manager__body{padding:20px}.target-manager__header{align-items:flex-start}.target-manager__count{display:none}.target-form{padding:18px}.target-form__grid{grid-template-columns:1fr}.target-field--representative{grid-column:auto}.target-form__footer{align-items:stretch;flex-direction:column}.target-save-button{width:100%}}
        @media(max-width:520px){.sales-delete-panel{padding:24px 18px}.sales-delete-actions{flex-direction:column-reverse}.sales-delete-actions button{width:100%}}
    </style>
@endsection

@push('scripts')
    <script>
        (() => {
            const modal = document.getElementById('target-delete-modal');
            const confirmButton = document.getElementById('target-delete-confirm');
            const countdownMessage = document.getElementById('target-delete-countdown-message');
            const countdown = document.getElementById('target-delete-countdown');
            let selectedForm = null;
            let selectedTrigger = null;
            let closeTimer = null;
            let countdownTimer = null;
            let countdownValue = 5;

            function resetCountdown() {
                clearInterval(countdownTimer);
                countdownTimer = null;
                countdownValue = 5;
                countdown.textContent = '5';
                countdownMessage.hidden = true;
                confirmButton.disabled = false;
                confirmButton.innerHTML = '<i class="bi bi-trash3 me-1"></i> Delete this record';
            }

            function openModal(trigger) {
                resetCountdown();
                selectedForm = document.getElementById(trigger.dataset.formId);
                selectedTrigger = trigger;

                if (!selectedForm) return;

                document.getElementById('target-delete-record-heading').textContent =
                    `Sales target for ${trigger.dataset.representative}`;
                document.getElementById('target-delete-period').textContent = trigger.dataset.period;
                document.getElementById('target-delete-orders').textContent = trigger.dataset.orders;
                document.getElementById('target-delete-revenue').textContent = trigger.dataset.revenue;

                clearTimeout(closeTimer);
                modal.hidden = false;
                document.body.classList.add('sales-delete-modal-open');
                requestAnimationFrame(() => {
                    modal.classList.add('is-open');
                    confirmButton.focus();
                });
            }

            function closeModal() {
                resetCountdown();
                modal.classList.remove('is-open');
                document.body.classList.remove('sales-delete-modal-open');
                closeTimer = setTimeout(() => {
                    modal.hidden = true;
                    selectedTrigger?.focus();
                    selectedForm = null;
                    selectedTrigger = null;
                }, 220);
            }

            document.querySelectorAll('[data-delete-target]').forEach(trigger => {
                trigger.addEventListener('click', () => openModal(trigger));
            });
            document.querySelectorAll('[data-target-delete-cancel]').forEach(button => {
                button.addEventListener('click', closeModal);
            });
            modal.addEventListener('click', event => {
                if (event.target === modal) closeModal();
            });
            document.addEventListener('keydown', event => {
                if (event.key === 'Escape' && !modal.hidden) closeModal();
            });
            confirmButton.addEventListener('click', () => {
                if (!selectedForm || countdownTimer) return;

                confirmButton.disabled = true;
                countdownMessage.hidden = false;
                countdown.textContent = countdownValue;
                confirmButton.innerHTML =
                    `<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Deleting in ${countdownValue}s`;

                countdownTimer = setInterval(() => {
                    countdownValue -= 1;
                    countdown.textContent = countdownValue;
                    confirmButton.innerHTML =
                        `<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Deleting in ${countdownValue}s`;

                    if (countdownValue <= 0) {
                        clearInterval(countdownTimer);
                        countdownTimer = null;
                        confirmButton.innerHTML =
                            '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Deleting...';
                        selectedForm.submit();
                    }
                }, 1000);
            });
        })();
    </script>
@endpush
