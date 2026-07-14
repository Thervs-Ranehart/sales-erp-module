@extends('layouts.app')

@section('content')
    @php
        $title = 'Sales Analysis';
        $subtitle = 'Detailed breakdown of sales by product, region, and representative';
    @endphp

    @php
        $tabs = [
            ['key' => 'product', 'label' => 'Sales by Product'],
            ['key' => 'region', 'label' => 'Sales by Region'],
            ['key' => 'representative', 'label' => 'Sales by Representative'],
        ];
    @endphp

    @php
        $activeTab = request('tab', 'product');
        $activeTab = in_array($activeTab, ['product', 'region', 'representative'], true)
            ? $activeTab
            : 'product';
    @endphp

    <section class="mt-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <ul class="nav nav-tabs" role="tablist">
                    @foreach($tabs as $t)
                        <li class="nav-item" role="presentation">
                            <a
                                class="nav-link {{ $activeTab === $t['key'] ? 'active' : '' }}"
                                href="{{ route('forecasting.sales-analysis', ['tab' => $t['key']]) }}"
                                role="tab"
                                aria-selected="{{ $activeTab === $t['key'] ? 'true' : 'false' }}"
                            >
                                {{ $t['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="d-flex">
                <a href="{{ route('forecasting.reports') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Sales Reports
                </a>
            </div>
        </div>
    </section>

    <section class="mt-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 px-4 py-3">
                <h3 id="sales-analysis-detail-title" class="m-0 fs-5 fw-bold text-gray-900">
                    {{ $tabs[array_search($activeTab, array_column($tabs, 'key'))]['label'] ?? 'Sales Analysis' }}
                </h3>
            </div>

            <div class="card-body px-0 px-sm-3 px-md-4 py-3">
                <div class="row g-4">
                    {{-- Reuse the same existing chart components from `forecasting/reports.blade.php` --}}
                    <div class="col-12">
                        @if ($activeTab === 'product')
                            @include('components.top-products-horizontal-bar', ['initialData' => $topProducts ?? null])
                        @elseif ($activeTab === 'region')
                            @include('components.sales-by-region-horizontal-bar', ['initialData' => $salesByRegion ?? null])
                        @else
                            @include('components.sales-by-representative-horizontal-bar', ['initialData' => $salesByRepresentative ?? null])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

