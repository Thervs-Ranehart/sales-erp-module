@extends('layouts.app')

@section('content')
    @php($title = 'Sales Reports')
    @php($subtitle = 'Review performance and generate actionable insights')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    {{-- Hero / Intro --}}
    <main class="bg-gradient-to-r from-[#0F8DA0] to-[#26E0C3] h-[230px] rounded-b-[12px] w-full flex items-center" style="padding-left: 48px;">
        <div>
            <h1 class="text-white font-bold text-[48px] leading-none">Sales Reports</h1>
            <p class="text-white/95 text-[18px] leading-[1.6]" style="max-width: 650px;">
                Monitor sales performance, identify trends, and generate business insights.
                Analyze revenue, product performance, regional sales, and employee achievements.
            </p>
            <a
                href="{{ route('forecasting.reports') }}"
                class="inline-flex items-center justify-center mt-4 px-6 py-3 rounded-[8px] border border-white text-white hover:bg-white hover:text-[#0F8DA0] transition-colors duration-300"
            >
                View Reports &rarr;
            </a>
        </div>
    </main>

    <section class="mt-5">
        <div class="card p-4">
            <h5 class="fw-bold mb-3">Report Highlights</h5>
            <p class="text-muted mb-0">
                This section will display charts and tables for sales summaries, top products, key accounts,
                and period comparisons.
            </p>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold">Revenue Trend</h6>
                    <p class="text-muted mb-0">Quarterly progression and growth metrics.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold">Top Products</h6>
                    <p class="text-muted mb-0">Ranked by volume and profitability.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold">Regional Performance</h6>
                    <p class="text-muted mb-0">Breakdown by area/territory.</p>
                </div>
            </div>
        </div>
    </section>
@endsection

