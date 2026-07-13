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
                    href="{{ route('forecasting.reports') }}"
                    class="hero-action-btn"
                    style="padding: 0.75rem 1.6rem;"
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
                    <div class="card p-3 h-100 border-0 shadow-sm" style="background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%); min-height: 112px;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-semibold small text-muted">{{ $kpi['label'] }}</span>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: {{ $kpi['accent'] }};">
                                <i class="bi bi-{{ $kpi['icon'] }} fs-6 {{ $kpi['tone'] }}"></i>
                            </div>
                        </div>
                        <div class="fw-bold fs-4 {{ $kpi['tone'] }}">{{ $kpi['value'] }}</div>
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

        </div>
    </section>

    {{-- Revenue trend chart section (reusable component) --}}
    @include('components.revenue-trend', ['initialData' => $monthlyRevenue])

    @include('components.report-filter')
@endsection

