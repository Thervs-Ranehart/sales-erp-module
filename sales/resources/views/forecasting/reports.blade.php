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

    <main class="h-[230px] w-full flex items-center" style="margin-left: -1rem; margin-right: -1rem; padding-left: 48px; padding-right: 48px; background: linear-gradient(90deg, #128B99 0%, #1CE5BD 100%);">
        <div class="flex flex-col justify-center gap-3">
            <h1 class="text-white font-bold text-[48px] leading-none">Sales Reports</h1>
            <p class="text-white/95 text-[18px] leading-[1.6]" style="max-width: 650px;">
                Monitor sales performance, identify trends, and generate business insights.
                Analyze revenue, product performance, regional sales, and employee achievements.
            </p>
            <a
                href="{{ route('forecasting.reports') }}"
                class="inline-flex items-center justify-center px-6 py-3 rounded-[8px] border border-white text-white hover:bg-white hover:text-[#0F8DA0] transition-colors duration-300 w-fit"
            >
                View Reports
            </a>
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

            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-funnel me-2"></i>
                Filter
            </button>
        </div>
    </section>
@endsection

