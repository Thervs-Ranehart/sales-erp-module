@extends('layouts.app')

@section('content')
    @php($title = 'Target vs. Actual Performance')
    @php($subtitle = 'Compare targets, track variances, and spot opportunities')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    {{-- Hero / Intro --}}
    <main class="bg-gradient-to-r from-[#0F8DA0] to-[#26E0C3] h-[230px] rounded-b-[12px] w-full flex items-center" style="padding-left: 48px;">
        <div>
            <h1 class="text-white font-bold text-[48px] leading-none">Target vs. Actual</h1>
            <p class="text-white/95 text-[18px] leading-[1.6]" style="max-width: 650px;">
                Assess plan attainment, analyze variance drivers, and identify areas that need reinforcement.
                Understand whether performance is ahead, on track, or falling behind.
            </p>
            <a
                href="{{ route('forecasting.performance') }}"
                class="inline-flex items-center justify-center mt-4 px-6 py-3 rounded-[8px] border border-white text-white hover:bg-white hover:text-[#0F8DA0] transition-colors duration-300"
            >
                View Performance &rarr;
            </a>
        </div>
    </main>

    <section class="mt-5">
        <div class="card p-4">
            <h5 class="fw-bold mb-3">Performance Variance</h5>
            <p class="text-muted mb-0">
                This section will show target achievement summaries, variance tables, and trend charts
                to support forecasting decisions.
            </p>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-md-6">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold">On-Track Accounts</h6>
                    <p class="text-muted mb-0">Accounts meeting target expectations.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold">At-Risk Accounts</h6>
                    <p class="text-muted mb-0">Accounts that need immediate follow-up.</p>
                </div>
            </div>
        </div>
    </section>
@endsection

