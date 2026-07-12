@extends('layouts.app')

@section('content')
    @php($title = 'Sales Performance Reporting and Forecasting')
    @php($subtitle = 'Track projections and sales performance trends')

@include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

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

    {{-- Content intentionally removed for Sales Performance Reporting and Forecasting submenu pages --}}
    {{-- Sales Reports / Target vs. Actual Performance / Forecasting / Recommendations will be populated later --}}
@endsection
