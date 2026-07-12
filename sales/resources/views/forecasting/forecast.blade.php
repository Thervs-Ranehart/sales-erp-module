@extends('layouts.app')

@section('content')
    @php($title = 'Forecasting')
    @php($subtitle = 'Project revenue and pipeline outcomes for upcoming periods')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    {{-- Hero / Intro --}}
    <main class="bg-gradient-to-r from-[#0F8DA0] to-[#26E0C3] h-[230px] rounded-b-[12px] w-full flex items-center" style="padding-left: 48px;">
        <div>
            <h1 class="text-white font-bold text-[48px] leading-none">Forecasting</h1>
            <p class="text-white/95 text-[18px] leading-[1.6]" style="max-width: 650px;">
                Forecast future performance using pipeline signals and historical trends.
                Build reliable projections to align resources and execution plans.
            </p>
            <a
                href="{{ route('forecasting.forecast') }}"
                class="inline-flex items-center justify-center mt-4 px-6 py-3 rounded-[8px] border border-white text-white hover:bg-white hover:text-[#0F8DA0] transition-colors duration-300"
            >
                View Forecast &rarr;
            </a>
        </div>
    </main>

    <section class="mt-5">
        <div class="card p-4">
            <h5 class="fw-bold mb-3">Forecast Inputs</h5>
            <p class="text-muted mb-0">
                This section will later connect to forecasting models, pipeline assumptions, and scenario comparisons.
            </p>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold">Pipeline Coverage</h6>
                    <p class="text-muted mb-0">Ensure sufficient deal flow for the period.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold">Win Rates</h6>
                    <p class="text-muted mb-0">Adjust probability based on history.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold">Scenario Planning</h6>
                    <p class="text-muted mb-0">Conservative, base, and aggressive scenarios.</p>
                </div>
            </div>
        </div>
    </section>
@endsection

