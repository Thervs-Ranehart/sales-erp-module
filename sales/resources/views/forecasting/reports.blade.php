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
        ['label' => 'Total Revenue', 'value' => '₱'.number_format($reportKpis['totalRevenue']), 'icon' => 'currency-dollar', 'tone' => 'text-success', 'accent' => 'rgba(22, 200, 199, 0.12)'],
        ['label' => 'Total Orders', 'value' => number_format($reportKpis['totalOrders']), 'icon' => 'cart-check', 'tone' => 'text-primary', 'accent' => 'rgba(83, 71, 206, 0.12)'],
        ['label' => 'Avg. Order Value', 'value' => '₱'.number_format($reportKpis['averageOrderValue']), 'icon' => 'graph-up', 'tone' => 'text-warning', 'accent' => 'rgba(245, 158, 11, 0.14)'],
        ['label' => 'Active Customers', 'value' => number_format($reportKpis['activeCustomers']), 'icon' => 'people-fill', 'tone' => 'text-info', 'accent' => 'rgba(72, 150, 254, 0.14)'],
        ['label' => 'Full-Year Growth', 'value' => '+'.number_format($reportKpis['salesGrowth'], 1).'%', 'icon' => 'bar-chart-line', 'tone' => 'text-success', 'accent' => 'rgba(16, 185, 129, 0.13)'],
        ['label' => 'Best Revenue Month', 'value' => $reportKpis['bestMonth'], 'icon' => 'calendar2-check', 'tone' => 'text-primary', 'accent' => 'rgba(83, 71, 206, 0.12)'],
    ])

    <main class="w-full flex items-center" style="margin: -1rem -1rem 0; width: calc(100% + 2rem); padding: 32px 48px; height: 258px; max-height: 258px; background: linear-gradient(90deg, #128B99 0%, #1CE5BD 100%); border-radius: 0;">
        <div class="d-flex flex-column h-100 w-100">
            <div>
                <h1 class="text-white font-semibold text-[45px] leading-tight">Sales Reports</h1>
            </div>
            <div class="flex-grow-1 d-flex align-items-center">
                <p class="text-white" style="width: 100%; margin: 0; font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 600; line-height: 1.6; opacity: 0.95;">
                    Monitor sales performance, identify trends, and generate business insights.
                    Analyze revenue, product performance, regional sales, and employee achievements.
                </p>
            </div>
            <div>
                <a
                    href="{{ route('forecasting.export', array_merge(['type' => 'reports', 'format' => 'csv'], request()->query())) }}"
                    class="hero-action-btn"
                    style="padding: 0.75rem 1.6rem;"
                >
                    <i class="bi bi-download me-2" aria-hidden="true"></i>
                    Export Reports
                </a>
                <a href="{{ route('forecasting.export', array_merge(['type' => 'reports', 'format' => 'print'], request()->query())) }}" target="_blank" class="hero-secondary-btn ms-2"><i class="bi bi-printer me-2"></i>Print / PDF</a>
            </div>
        </div>
    </main>

    <section class="mt-4">
        <div class="row g-4">
            @foreach ($kpis as $kpi)
                <div class="col-md-6 col-lg-4">
                    <div class="card px-3 py-2 h-100 border-0 shadow-sm" style="background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%); min-height: 92px;">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fw-semibold small text-muted">{{ $kpi['label'] }}</span>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: {{ $kpi['accent'] }};">
                                <i class="bi bi-{{ $kpi['icon'] }} fs-6 {{ $kpi['tone'] }}"></i>
                            </div>
                        </div>
                        <div class="fw-bold fs-4 {{ $kpi['tone'] }}">{{ $kpi['value'] }}</div>
                        <p class="text-muted small mb-0 mt-1">Updated from live sales data</p>
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

            <button id="rf-open-filter" class="btn btn-outline-secondary btn-sm" type="button" aria-controls="rf-filter-drawer" aria-expanded="false">
                <i class="bi bi-funnel me-2"></i>
                Filter
            </button>

        </div>
    </section>

    {{-- Revenue trend chart section (reusable component) --}}
    @isset($monthlyRevenue)
        @include('components.revenue-trend', ['initialData' => $monthlyRevenue])
    @endisset

    <section class="mt-5" aria-labelledby="sales-analysis-title">
        <div class="d-flex align-items-end justify-content-between gap-3 mb-3">
            <div><h2 id="sales-analysis-title" class="text-lg font-bold text-gray-900 mb-1">Sales Analysis</h2><p class="small text-muted mb-0">Compare the strongest contributors across products, regions, and representatives.</p></div>
        </div>
        <div class="row gx-4 gy-5">
                    <div class="col-12 d-flex flex-column">
                        @include('components.top-products-horizontal-bar', ['initialData' => $topProducts ?? null])
                        <div class="d-flex justify-content-end pt-3 pb-1 position-relative" style="z-index: 2;">
                            <a href="{{ route('forecasting.sales-analysis', ['tab' => 'product']) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-2" target="_blank" rel="noopener noreferrer">View More <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                        </div>
                    </div>

                    <div class="col-12 col-xl-6 d-flex flex-column">
                        @include('components.sales-by-region-horizontal-bar', ['initialData' => $salesByRegion])
                        <div class="d-flex justify-content-end pt-3 pb-1 position-relative" style="z-index: 2;">
                            <a href="{{ route('forecasting.sales-analysis', ['tab' => 'region']) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-2" target="_blank" rel="noopener noreferrer">View More <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                        </div>
                    </div>

                    <div class="col-12 col-xl-6 d-flex flex-column">
                        @include('components.sales-by-representative-horizontal-bar', ['initialData' => $salesByRepresentative])
                        <div class="d-flex justify-content-end pt-3 pb-1 position-relative" style="z-index: 2;">
                            <a href="{{ route('forecasting.sales-analysis', ['tab' => 'representative']) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-2" target="_blank" rel="noopener noreferrer">View More <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
        </div>
    </section>

    <section class="mt-4" aria-labelledby="warehouse-sales-title"><div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4"><h2 id="warehouse-sales-title" class="fs-5 fw-bold mb-1">Sales by Fulfillment Warehouse</h2><p class="small text-muted">Operational warehouse performance is reported separately from customer geography.</p><div class="row g-3 mt-1">@forelse($salesByWarehouse['labels'] as $index=>$warehouse)<div class="col-md-4"><div class="border rounded-3 p-3"><div class="small text-muted">{{ $warehouse }}</div><div class="fw-bold">₱{{ number_format($salesByWarehouse['values'][$index] ?? 0) }}</div></div></div>@empty<div class="text-muted">No warehouse values are available for this period.</div>@endforelse</div></div></div></section>

    <section class="mt-5" aria-labelledby="region-management-title"><div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2"><div><h2 id="region-management-title" class="fs-5 fw-bold mb-1">Sales Region Management</h2><p class="small text-muted mb-0">Regions are assigned to customers independently from fulfillment warehouses.</p></div><button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createRegionModal"><i class="bi bi-plus-lg me-1"></i>Add Region</button></div>
        <div class="row g-3 mt-2"><div class="col-lg-5"><h3 class="fs-6 fw-semibold">Configured Regions</h3><div class="list-group">@forelse($salesRegions as $region)<div class="list-group-item d-flex justify-content-between"><span>{{ $region->region_name }} <small class="text-muted">({{ $region->region_code }})</small></span><span class="badge text-bg-light">{{ $region->customers_count }} customers</span></div>@empty<div class="text-muted">No dedicated regions configured yet.</div>@endforelse</div></div>
        <div class="col-lg-7"><h3 class="fs-6 fw-semibold">Customer Assignments</h3><div class="table-responsive" style="max-height:280px"><table class="table table-sm"><thead><tr><th>Customer</th><th>Region</th></tr></thead><tbody>@foreach($regionCustomers as $customer)<tr><td>{{ $customer->full_name }}</td><td><form method="POST" action="{{ route('forecasting.customers.region', $customer) }}">@csrf @method('PATCH')<select name="region_id" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">Unassigned</option>@foreach($salesRegions as $region)<option value="{{ $region->region_id }}" @selected($customer->region_id===$region->region_id)>{{ $region->region_name }}</option>@endforeach</select></form></td></tr>@endforeach</tbody></table></div></div></div>
    </div></div></section>
    <div class="modal fade" id="createRegionModal" tabindex="-1"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('forecasting.regions.store') }}">@csrf<div class="modal-header"><h5 class="modal-title">Add Sales Region</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><label class="form-label">Region Code</label><input class="form-control mb-3" name="region_code" required><label class="form-label">Region Name</label><input class="form-control mb-3" name="region_name" required><label class="form-label">Country</label><input class="form-control" name="country" value="Philippines" required></div><div class="modal-footer"><button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary">Save Region</button></div></form></div></div>

    <section class="mt-5" aria-labelledby="monthly-report-title">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 px-3 px-md-4 pt-4 pb-3">
                <h2 id="monthly-report-title" class="fs-5 fw-bold mb-1">Monthly Sales Detail</h2>
                <p class="small text-muted mb-0">The underlying monthly figures used in the revenue trend.</p>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th class="ps-3 ps-md-4">Period</th><th>Revenue</th><th>Orders</th><th>Average Order Value</th><th class="pe-3 pe-md-4">Monthly Growth</th></tr></thead>
                    <tbody>
                        @foreach ($monthlyReportRows as $row)
                            <tr><th scope="row" class="ps-3 ps-md-4">{{ $row['period'] }}</th><td class="text-nowrap fw-semibold">₱{{ number_format($row['revenue']) }}</td><td>{{ number_format($row['orders']) }}</td><td class="text-nowrap">₱{{ number_format($row['averageOrderValue']) }}</td><td class="pe-3 pe-md-4"><span class="badge rounded-pill {{ is_null($row['growth']) ? 'text-bg-secondary' : ($row['growth'] >= 0 ? 'text-bg-success' : 'text-bg-danger') }}">{{ is_null($row['growth']) ? 'Baseline' : (($row['growth'] >= 0 ? '+' : '').number_format($row['growth'], 1).'%') }}</span></td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="mt-5 mb-4" aria-labelledby="report-insights-title">
        <h2 id="report-insights-title" class="text-lg font-bold text-gray-900 mb-3">Report Insights</h2>
        <div class="row g-3">
            @foreach ($reportInsights as $insight)
                <div class="col-12 col-lg-6"><div class="alert {{ $insight['type'] === 'success' ? 'alert-success' : ($insight['type'] === 'warning' ? 'alert-warning' : 'alert-info') }} h-100 mb-0 d-flex gap-3 align-items-start"><i class="bi {{ $insight['type'] === 'warning' ? 'bi-exclamation-triangle-fill' : 'bi-bar-chart-fill' }} mt-1" aria-hidden="true"></i><span>{{ $insight['text'] }}</span></div></div>
            @endforeach
        </div>
    </section>

    @include('components.report-filter', ['filterMode' => 'reports'])

@endsection
