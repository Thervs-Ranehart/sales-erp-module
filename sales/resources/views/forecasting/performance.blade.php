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
                <button
                    type="button"
                    class="hero-action-btn"
                    style="padding: 0.75rem 1.6rem;"
                >
                    <i class="bi bi-download me-2"></i>
                    Export Performance Data
                </button>
            </div>
        </div>
    </main>

    {{-- KPI Cards --}}
    <section class="mt-4" aria-label="Target versus actual KPI summary">
        <div class="row g-4">
            @foreach ($performanceKpis as $performanceKpi)
                <div class="col-md-6 col-lg-4">
                    <article
                        class="card p-3 h-100 border-0 shadow-sm"
                        style="
                            background: linear-gradient(
                                145deg,
                                #ffffff 0%,
                                #f8fafc 100%
                            );
                            min-height: 112px;
                        "
                    >
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-semibold small text-muted">
                                {{ $performanceKpi['label'] }}
                            </span>

                            <div
                                class="rounded-circle d-flex align-items-center justify-content-center"
                                style="
                                    width: 36px;
                                    height: 36px;
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

                        <p class="text-muted small mb-0 mt-2">
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
        <h2 id="performance-breakdown-heading" class="text-lg font-bold text-gray-900 mb-3">Performance Breakdown</h2>
        <div class="row g-4">
            <div class="col-12 col-xl-4">
                <x-performance-percentage-chart :initial-data="$achievementByProduct" chart-id="achievementByProductChart" title="Achievement by Product" />
            </div>
            <div class="col-12 col-xl-4">
                <x-performance-percentage-chart :initial-data="$achievementByRegion" chart-id="achievementByRegionChart" title="Achievement by Region" />
            </div>
            <div class="col-12 col-xl-4">
                <x-performance-percentage-chart :initial-data="$achievementByRepresentative" chart-id="achievementByRepresentativeChart" title="Achievement by Representative" />
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
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h2 id="sales-targets-heading" class="fs-5 fw-bold mb-1">Manage Sales Targets</h2>
                <p class="small text-muted">Saving the same representative, month, and year updates the existing target.</p>
                @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('forecasting.targets.store') }}" class="row g-3 align-items-end">
                    @csrf
                    <div class="col-md-4"><label class="form-label" for="target-employee">Representative</label><select class="form-select" id="target-employee" name="employee_id" required>@foreach($employees as $employee)<option value="{{ $employee->employee_id }}">{{ $employee->full_name }}</option>@endforeach</select></div>
                    <div class="col-6 col-md-2"><label class="form-label" for="target-month">Month</label><select class="form-select" id="target-month" name="target_month" required>@foreach(range(1, 12) as $month)<option value="{{ $month }}" @selected($month === now()->month)>{{ now()->setMonth($month)->format('F') }}</option>@endforeach</select></div>
                    <div class="col-6 col-md-2"><label class="form-label" for="target-year">Year</label><input class="form-control" id="target-year" name="target_year" type="number" min="2020" max="2100" value="{{ request('year', now()->year) }}" required></div>
                    <div class="col-6 col-md-2"><label class="form-label" for="sales-target">Order target</label><input class="form-control" id="sales-target" name="sales_target" type="number" min="0" value="0" required></div>
                    <div class="col-6 col-md-2"><label class="form-label" for="revenue-target">Revenue target</label><input class="form-control" id="revenue-target" name="revenue_target" type="number" min="0" step="0.01" required></div>
                    <div class="col-12"><button class="btn btn-primary" type="submit"><i class="bi bi-bullseye me-2"></i>Save Target</button></div>
                </form>
                <div class="table-responsive mt-4"><table class="table table-sm align-middle"><thead><tr><th>Representative</th><th>Period</th><th>Orders</th><th>Revenue</th><th></th></tr></thead><tbody>@forelse($salesTargets as $target)<tr><td>{{ $target->employee?->full_name }}</td><td>{{ now()->setMonth($target->target_month)->format('M') }} {{ $target->target_year }}</td><td>{{ number_format($target->sales_target) }}</td><td>₱{{ number_format($target->revenue_target, 2) }}</td><td><form method="POST" action="{{ route('forecasting.targets.destroy', $target) }}" onsubmit="return confirm('Delete this target?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit">Delete</button></form></td></tr>@empty<tr><td colspan="5" class="text-center text-muted py-3">No targets have been created.</td></tr>@endforelse</tbody></table></div>
            </div>
        </div>
    </section>

    @include('components.report-filter', ['filterMode' => 'performance'])
@endsection
