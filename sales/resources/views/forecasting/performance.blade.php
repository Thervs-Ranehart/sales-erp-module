@extends('layouts.app')

@section('content')
    @php($title = 'Target vs. Actual Performance')
    @php($subtitle = 'Compare targets, track variances, and spot opportunities')

    @php($tabs = [
        ['label' => 'Sales Reports', 'route' => 'forecasting.reports'],
        ['label' => 'Target vs. Actual', 'route' => 'forecasting.performance'],
        ['label' => 'Forecasting', 'route' => 'forecasting.forecast'],
        ['label' => 'Recommendations', 'route' => 'forecasting.recommendations'],
    ])

    @php($kpis = [
        ['label' => 'Revenue vs. Target', 'value' => '₱4.2M / ₱4.5M', 'icon' => 'cash-stack', 'tone' => 'text-success', 'accent' => 'rgba(22, 200, 199, 0.12)'],
        ['label' => 'Order Completion', 'value' => '89%', 'icon' => 'check2-circle', 'tone' => 'text-primary', 'accent' => 'rgba(83, 71, 206, 0.12)'],
        ['label' => 'Variance', 'value' => '-6.7%', 'icon' => 'arrow-down-right', 'tone' => 'text-warning', 'accent' => 'rgba(245, 158, 11, 0.14)'],
        ['label' => 'Top Accounts', 'value' => '24', 'icon' => 'person-badge', 'tone' => 'text-info', 'accent' => 'rgba(72, 150, 254, 0.14)'],
        ['label' => 'Goal Alignment', 'value' => '92%', 'icon' => 'bullseye', 'tone' => 'text-danger', 'accent' => 'rgba(239, 68, 68, 0.13)'],
        ['label' => 'Pipeline Coverage', 'value' => '3.1x', 'icon' => 'diagram-3', 'tone' => 'text-success', 'accent' => 'rgba(16, 185, 129, 0.14)'],
    ])

    <main class="w-full flex items-center" style="margin: -1rem -1rem 0; width: calc(100% + 2rem); padding: 40px 48px; height: 322px; max-height: 322px; background: linear-gradient(90deg, #128B99 0%, #1CE5BD 100%); border-radius: 0;">
        <div class="d-flex flex-column h-100 w-100">
            <div>
                <h1 class="text-white font-semibold text-[56px] leading-tight">Target vs. Actual</h1>
            </div>
            <div class="flex-grow-1 d-flex align-items-center">
                <p class="text-white" style="width: 100%; margin: 0; font-family: 'Poppins', sans-serif; font-size: 22px; font-weight: 600; line-height: 1.6; opacity: 0.95;">
                    Assess plan attainment, analyze variance drivers, and identify areas that need reinforcement.
                </p>
            </div>
            <div>
                <a
                    href="{{ route('forecasting.performance') }}"
                    class="hero-action-btn"
                >
                    View Performance
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
                        <p class="text-muted small mb-0 mt-2">Performance snapshot</p>
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

