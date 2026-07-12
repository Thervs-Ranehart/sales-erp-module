@extends('layouts.app')

@section('content')
    @php($title = 'Recommendations')
    @php($subtitle = 'Actionable next steps based on your forecast performance')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    {{-- Hero / Intro --}}
    <main class="bg-gradient-to-r from-[#0F8DA0] to-[#26E0C3] h-[230px] rounded-b-[12px] w-full flex items-center" style="padding-left: 48px;">
        <div>
            <h1 class="text-white font-bold text-[48px] leading-none">Recommendations</h1>
            <p class="text-white/95 text-[18px] leading-[1.6]" style="max-width: 650px;">
                Discover recommended actions to improve pipeline health, close gaps, and increase forecast accuracy.
                Prioritize initiatives that drive measurable impact.
            </p>
            <a
                href="{{ route('forecasting.recommendations') }}"
                class="inline-flex items-center justify-center mt-4 px-6 py-3 rounded-[8px] border border-white text-white hover:bg-white hover:text-[#0F8DA0] transition-colors duration-300"
            >
                View Recommendations &rarr;
            </a>
        </div>
    </main>

    <section class="mt-5">
        <div class="card p-4">
            <h5 class="fw-bold mb-3">Suggested Actions</h5>
            <p class="text-muted mb-0">
                This section will show prioritized recommendations for sales execution, follow-ups, and pipeline improvements.
            </p>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-md-6">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold">Improve Coverage</h6>
                    <p class="text-muted mb-0">Add deals to reduce forecast risk.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold">Accelerate Deal Cycles</h6>
                    <p class="text-muted mb-0">Reduce cycle time through targeted follow-ups.</p>
                </div>
            </div>
        </div>
    </section>
@endsection

