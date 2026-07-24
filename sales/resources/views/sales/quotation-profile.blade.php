@extends('layouts.app')

@section('title', 'Quotation Details')
@section('page-title', 'Sales Order Management')

@section('content')
    @include('sales.partials.alerts')

    <div class="container-fluid px-0">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <a href="{{ route('quotations.index') }}" class="text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i>Back to quotations
                </a>
                <h2 class="fw-bold mt-2 mb-1">{{ $quotation->quotation_number }}</h2>
                <p class="text-muted mb-0">
                    {{ $quotation->customer?->full_name ?? 'Unknown customer' }}
                    · {{ $quotation->quotation_date?->format('M d, Y') }}
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                @if ($convertedOrder)
                    <a href="{{ route('sales.profile', $convertedOrder) }}" class="btn btn-success">
                        <i class="bi bi-box-arrow-up-right me-1"></i>View {{ $convertedOrder->order_number }}
                    </a>
                @elseif (strtolower((string) $quotation->quotation_status) === 'accepted' && ! $quotation->valid_until?->isPast())
                    <form method="POST" action="{{ route('quotations.convert', $quotation) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-arrow-left-right me-1"></i>Convert to Sales Order
                        </button>
                    </form>
                @endif

                @if (! $convertedOrder && strtolower((string) $quotation->quotation_status) !== 'accepted')
                    <a href="{{ route('quotations.edit', $quotation) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                @endif
                <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i>Print
                </button>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Quoted Items</h5>
                            <span class="badge text-bg-light border">{{ $quotation->items->count() }} item(s)</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-end">Quantity</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Line Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotation->items as $item)
                                        <tr>
                                            <td>{{ $item->product?->product_name ?? 'Product #'.$item->product_id }}</td>
                                            <td class="text-end">{{ number_format($item->quantity) }}</td>
                                            <td class="text-end">₱{{ number_format((float) $item->unit_price, 2) }}</td>
                                            <td class="text-end fw-semibold">₱{{ number_format((float) $item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Quotation Status</h5>
                        <dl class="row mb-0">
                            <dt class="col-6 text-muted">Status</dt>
                            <dd class="col-6 text-end fw-semibold">{{ $quotation->formattedStatus() }}</dd>
                            <dt class="col-6 text-muted">Valid until</dt>
                            <dd class="col-6 text-end">{{ $quotation->valid_until?->format('M d, Y') ?? '—' }}</dd>
                            <dt class="col-6 text-muted">Pricing rule</dt>
                            <dd class="col-6 text-end">{{ $quotation->pricingRule?->rule_name ?? 'Manual pricing' }}</dd>
                        </dl>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Totals</h5>
                        <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span>₱{{ number_format((float) $quotation->subtotal, 2) }}</span></div>
                        <div class="d-flex justify-content-between mb-2"><span>Discount</span><span>− ₱{{ number_format((float) $quotation->discount, 2) }}</span></div>
                        <div class="d-flex justify-content-between mb-3"><span>Tax</span><span>₱{{ number_format((float) $quotation->tax, 2) }}</span></div>
                        <div class="d-flex justify-content-between border-top pt-3 fs-5 fw-bold"><span>Total</span><span>₱{{ number_format((float) $quotation->total_amount, 2) }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
