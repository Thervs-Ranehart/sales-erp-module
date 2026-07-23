@extends('layouts.app')

@section('title', 'Sales Order Details')
@section('page-title', 'Sales Order Management')

@section('content')
    @php
        $status = strtolower((string) $order->order_status);
        $statusTone = match ($status) {
            'delivered' => 'success',
            'shipped' => 'info',
            'processed' => 'primary',
            'cancelled' => 'danger',
            default => 'warning',
        };
        $statusIcon = match ($status) {
            'delivered' => 'check-circle-fill',
            'shipped' => 'truck',
            'processed' => 'gear-fill',
            'cancelled' => 'x-circle-fill',
            default => 'clock-fill',
        };
        $customerInitials = collect([
            $order->customer?->first_name,
            $order->customer?->last_name,
        ])->filter()->map(fn ($name) => strtoupper(mb_substr($name, 0, 1)))->join('') ?: 'NA';
    @endphp

    @include('sales.partials.alerts')

    <div class="order-profile-page">
        <nav class="order-breadcrumb mb-3" aria-label="Breadcrumb">
            <a href="{{ route('sales.index') }}">Sales Orders</a>
            <i class="bi bi-chevron-right"></i>
            <span>{{ $order->order_number }}</span>
        </nav>

        <header class="order-profile-header mb-4">
            <div class="d-flex align-items-start gap-3">
                <a href="{{ route('sales.index') }}" class="order-back-button" aria-label="Back to sales orders">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h2 class="fw-bold mb-0">Sales Order {{ $order->order_number }}</h2>
                        <span class="order-status-badge is-{{ $statusTone }}">
                            <i class="bi bi-{{ $statusIcon }}"></i>
                            {{ $order->formattedStatus() ?: 'Pending' }}
                        </span>
                    </div>
                    <p class="text-muted mb-0">
                        Created {{ $order->created_at?->format('F j, Y \a\t g:i A') ?? 'date unavailable' }}
                        @if($order->employee)
                            by {{ $order->employee->full_name }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="order-header-actions">
                <a href="{{ route('sales.edit', $order) }}" class="btn order-secondary-button">
                    <i class="bi bi-pencil-square"></i>
                    Edit order
                </a>
                <a href="{{ route('invoices.create', ['order_id' => $order->order_id]) }}" class="btn order-primary-button">
                    <i class="bi bi-receipt"></i>
                    Create invoice
                </a>
            </div>
        </header>

        <section class="order-summary-strip mb-4" aria-label="Order summary">
            <div>
                <span>Order date</span>
                <strong>{{ $order->order_date?->format('M d, Y') ?? 'Not specified' }}</strong>
            </div>
            <div>
                <span>Payment</span>
                <strong>{{ $order->payment_status ?: 'Not specified' }}</strong>
                <small>{{ $order->payment_method ?: 'No payment method' }}</small>
            </div>
            <div>
                <span>Warehouse / Region</span>
                <strong>{{ $order->warehouse ?: 'Not assigned' }}</strong>
            </div>
            <div>
                <span>Items</span>
                <strong>{{ number_format($order->items->sum('quantity')) }}</strong>
                <small>{{ $order->items->count() }} product line(s)</small>
            </div>
            <div class="order-summary-total">
                <span>Order total</span>
                <strong>₱{{ number_format((float) $order->total_amount, 2) }}</strong>
            </div>
        </section>

        <div class="row g-4 mb-4">
            <div class="col-lg-7">
                <article class="card order-detail-card h-100">
                    <div class="order-card-heading">
                        <span class="order-heading-icon"><i class="bi bi-person-vcard"></i></span>
                        <div>
                            <h5 class="fw-bold mb-1">Customer Information</h5>
                            <p class="text-muted small mb-0">Billing and delivery contact for this order.</p>
                        </div>
                    </div>
                    <div class="order-customer-body">
                        <div class="order-customer-identity">
                            <span class="order-customer-avatar">{{ $customerInitials }}</span>
                            <div>
                                <strong>{{ $order->customer?->full_name ?? 'Customer unavailable' }}</strong>
                                <small>Customer #{{ str_pad((string) $order->customer_id, 4, '0', STR_PAD_LEFT) }}</small>
                            </div>
                        </div>
                        <div class="order-contact-grid">
                            <div>
                                <span><i class="bi bi-envelope"></i>Email address</span>
                                <strong>{{ $order->customer?->email ?: 'Not provided' }}</strong>
                            </div>
                            <div>
                                <span><i class="bi bi-telephone"></i>Phone number</span>
                                <strong>{{ $order->customer?->contact_no ?: 'Not provided' }}</strong>
                            </div>
                            <div class="order-address">
                                <span><i class="bi bi-geo-alt"></i>Shipping address</span>
                                <strong>{{ $order->customer?->address ?: 'Not provided' }}</strong>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <div class="col-lg-5">
                <article class="card order-detail-card h-100">
                    <div class="order-card-heading">
                        <span class="order-heading-icon"><i class="bi bi-box-seam"></i></span>
                        <div>
                            <h5 class="fw-bold mb-1">Order Information</h5>
                            <p class="text-muted small mb-0">Ownership and fulfillment details.</p>
                        </div>
                    </div>
                    <dl class="order-definition-list">
                        <div>
                            <dt>Sales representative</dt>
                            <dd>{{ $order->employee?->full_name ?: 'Not assigned' }}</dd>
                        </div>
                        <div>
                            <dt>Pricing rule</dt>
                            <dd>{{ $order->pricingRule?->rule_name ?: 'Standard pricing' }}</dd>
                        </div>
                        <div>
                            <dt>Quotation reference</dt>
                            <dd>{{ $order->quotation_id ? '#'.$order->quotation_id : 'Direct order' }}</dd>
                        </div>
                        <div>
                            <dt>Invoices generated</dt>
                            <dd>{{ $order->invoices->count() }}</dd>
                        </div>
                        <div>
                            <dt>Last updated</dt>
                            <dd>{{ $order->updated_at?->diffForHumans() ?? 'Not available' }}</dd>
                        </div>
                    </dl>
                </article>
            </div>
        </div>

        <section class="card order-detail-card mb-4">
            <div class="order-card-heading">
                <span class="order-heading-icon"><i class="bi bi-bag-check"></i></span>
                <div>
                    <h5 class="fw-bold mb-1">Order Items</h5>
                    <p class="text-muted small mb-0">Products, quantities, discounts, and line totals.</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table order-items-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-end">Unit price</th>
                            <th class="text-end">Discount</th>
                            <th class="text-end">Line total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->items as $item)
                            <tr>
                                <td>
                                    <div class="order-product">
                                        <span><i class="bi bi-box"></i></span>
                                        <div>
                                            <strong>{{ $item->product?->product_name ?? 'Product unavailable' }}</strong>
                                            <small>Product #{{ str_pad((string) $item->product_id, 4, '0', STR_PAD_LEFT) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="order-category">{{ $item->product?->category ?: 'Uncategorized' }}</span></td>
                                <td class="text-center fw-semibold">{{ number_format($item->quantity) }}</td>
                                <td class="text-end">₱{{ number_format((float) $item->unit_price, 2) }}</td>
                                <td class="text-end text-danger">
                                    {{ (float) $item->discount > 0 ? '-₱'.number_format((float) $item->discount, 2) : '—' }}
                                </td>
                                <td class="text-end fw-bold">₱{{ number_format((float) $item->subtotal, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="order-empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <strong>No products on this order</strong>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="row g-4">
            <div class="col-lg-7">
                <section class="card order-detail-card h-100">
                    <div class="order-card-heading">
                        <span class="order-heading-icon"><i class="bi bi-arrow-repeat"></i></span>
                        <div>
                            <h5 class="fw-bold mb-1">Order Status</h5>
                            <p class="text-muted small mb-0">Update the current fulfillment stage.</p>
                        </div>
                    </div>
                    <form action="{{ route('sales.update-status', $order) }}" method="POST" class="order-status-form">
                        @csrf
                        @method('PATCH')
                        <div class="order-status-options">
                            @foreach([
                                'pending' => ['clock', 'Pending'],
                                'processed' => ['gear', 'Processed'],
                                'shipped' => ['truck', 'Shipped'],
                                'delivered' => ['check-circle', 'Delivered'],
                                'cancelled' => ['x-circle', 'Cancelled'],
                            ] as $value => [$icon, $label])
                                <label class="order-status-option">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="{{ $value }}"
                                        @checked(old('status', $status) === $value)
                                    >
                                    <span>
                                        <i class="bi bi-{{ $icon }}"></i>
                                        {{ $label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('status')
                            <p class="text-danger small mb-3">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="order-update-button">
                            <i class="bi bi-check2-circle"></i>
                            Save status
                        </button>
                    </form>
                </section>
            </div>

            <div class="col-lg-5">
                <section class="card order-detail-card order-financial-card h-100">
                    <div class="order-card-heading">
                        <span class="order-heading-icon"><i class="bi bi-calculator"></i></span>
                        <div>
                            <h5 class="fw-bold mb-1">Financial Summary</h5>
                            <p class="text-muted small mb-0">Complete order amount breakdown.</p>
                        </div>
                    </div>
                    <div class="order-totals">
                        <div><span>Subtotal</span><strong>₱{{ number_format((float) $order->subtotal, 2) }}</strong></div>
                        <div><span>Discount{{ $order->discountPercent() > 0 ? ' ('.$order->discountPercent().'%)' : '' }}</span><strong class="text-danger">-₱{{ number_format((float) $order->discount, 2) }}</strong></div>
                        <div><span>Tax / VAT ({{ $order->taxPercent() }}%)</span><strong>₱{{ number_format((float) $order->tax, 2) }}</strong></div>
                        <div><span>Shipping fee</span><strong>₱{{ number_format((float) $order->shipping_fee, 2) }}</strong></div>
                        <div class="order-grand-total"><span>Grand total</span><strong>₱{{ number_format((float) $order->total_amount, 2) }}</strong></div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <style>
        .order-profile-page{max-width:1500px;margin:0 auto;padding:4px}.order-breadcrumb{display:flex;align-items:center;gap:8px;color:#94a3b8;font-size:11px}.order-breadcrumb a{color:#5347ce;font-weight:600;text-decoration:none}.order-breadcrumb i{font-size:8px}.order-profile-header{display:flex;align-items:center;justify-content:space-between;gap:24px}.order-profile-header h2{color:#172033;font-size:25px}.order-profile-header p{font-size:11px}.order-back-button{width:40px;height:40px;display:grid;place-items:center;flex:0 0 40px;border:1px solid #e2e8f0;border-radius:12px;color:#475569;background:#fff;text-decoration:none;box-shadow:0 5px 14px rgba(15,23,42,.05);transition:.2s}.order-back-button:hover{color:#fff;background:#5347ce;border-color:#5347ce;transform:translateX(-2px)}.order-status-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.04em}.order-status-badge.is-success{color:#047857;background:#ecfdf5}.order-status-badge.is-info{color:#0369a1;background:#f0f9ff}.order-status-badge.is-primary{color:#4338ca;background:#eef2ff}.order-status-badge.is-danger{color:#b91c1c;background:#fef2f2}.order-status-badge.is-warning{color:#b45309;background:#fffbeb}.order-header-actions{display:flex;gap:9px}.order-header-actions .btn{display:inline-flex;align-items:center;gap:7px;padding:10px 14px;border-radius:11px;font-size:11px;font-weight:700;text-decoration:none}.order-secondary-button{border:1px solid #dbe1ea;color:#475569;background:#fff}.order-secondary-button:hover{color:#4338ca;border-color:#a5b4fc;background:#f5f3ff}.order-primary-button{border:1px solid #5347ce;color:#fff;background:#5347ce}.order-primary-button:hover{color:#fff;background:#4338ca}.order-summary-strip{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));overflow:hidden;border:1px solid #e6eaf0;border-radius:17px;background:#fff;box-shadow:0 8px 24px rgba(15,23,42,.045)}.order-summary-strip>div{min-height:92px;display:flex;flex-direction:column;justify-content:center;padding:17px 20px;border-right:1px solid #eef2f7}.order-summary-strip>div:last-child{border-right:0}.order-summary-strip span,.order-summary-strip small{color:#64748b;font-size:9px}.order-summary-strip strong{margin:4px 0;color:#1e293b;font-size:13px}.order-summary-strip .order-summary-total{background:linear-gradient(135deg,#5347ce,#7569e8)}.order-summary-total span,.order-summary-total small{color:rgba(255,255,255,.75)}.order-summary-total strong{color:#fff;font-size:18px}.order-detail-card{overflow:hidden;border:1px solid #e6eaf0;box-shadow:0 8px 24px rgba(15,23,42,.045)}.order-card-heading{min-height:76px;display:flex;align-items:center;gap:12px;padding:18px 22px;border-bottom:1px solid #eef2f7}.order-heading-icon{width:40px;height:40px;display:grid;place-items:center;flex:0 0 40px;border-radius:12px;color:#5347ce;background:#f0efff;font-size:17px}.order-customer-body{padding:22px}.order-customer-identity{display:flex;align-items:center;gap:12px;margin-bottom:21px}.order-customer-avatar{width:49px;height:49px;display:grid;place-items:center;flex:0 0 49px;border-radius:14px;color:#fff;background:linear-gradient(135deg,#5347ce,#887cfd);font-size:15px;font-weight:800}.order-customer-identity strong,.order-customer-identity small{display:block}.order-customer-identity strong{color:#1e293b;font-size:14px}.order-customer-identity small{margin-top:3px;color:#64748b;font-size:9px}.order-contact-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.order-contact-grid>div{padding:13px 14px;border-radius:12px;background:#f8fafc}.order-contact-grid .order-address{grid-column:1/-1}.order-contact-grid span,.order-contact-grid strong{display:block}.order-contact-grid span{margin-bottom:5px;color:#64748b;font-size:9px}.order-contact-grid span i{margin-right:6px;color:#5347ce}.order-contact-grid strong{color:#334155;font-size:11px;line-height:1.5}.order-definition-list{margin:0;padding:10px 22px 16px}.order-definition-list>div{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:13px 0;border-bottom:1px solid #f1f5f9}.order-definition-list>div:last-child{border-bottom:0}.order-definition-list dt{color:#64748b;font-size:10px;font-weight:500}.order-definition-list dd{margin:0;color:#334155;font-size:11px;font-weight:700;text-align:right}.order-items-table th{padding:13px 18px;border-bottom:1px solid #e5e7eb;color:#64748b;background:#f8fafc;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap}.order-items-table td{padding:15px 18px;border-color:#f1f5f9;color:#475569;font-size:11px}.order-product{display:flex;align-items:center;gap:10px;min-width:190px}.order-product>span{width:35px;height:35px;display:grid;place-items:center;flex:0 0 35px;border-radius:10px;color:#5347ce;background:#f0efff}.order-product strong,.order-product small{display:block}.order-product strong{color:#1e293b;font-size:11px}.order-product small{margin-top:2px;color:#94a3b8;font-size:8px}.order-category{display:inline-block;padding:4px 8px;border-radius:999px;color:#0369a1;background:#f0f9ff;font-size:8px;font-weight:700}.order-empty-state{height:140px;text-align:center!important;color:#94a3b8!important}.order-empty-state i,.order-empty-state strong{display:block}.order-empty-state i{margin-bottom:7px;font-size:24px}.order-status-form{padding:22px}.order-status-options{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:9px;margin-bottom:18px}.order-status-option{cursor:pointer}.order-status-option input{position:absolute;opacity:0;pointer-events:none}.order-status-option span{min-height:72px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:7px;padding:9px;border:1px solid #e2e8f0;border-radius:12px;color:#64748b;background:#fff;font-size:9px;font-weight:700;transition:.2s}.order-status-option span i{font-size:17px}.order-status-option input:checked+span{border-color:#5347ce;color:#4338ca;background:#f0efff;box-shadow:0 0 0 3px rgba(83,71,206,.09)}.order-status-option:hover span{border-color:#a5b4fc;transform:translateY(-2px)}.order-update-button{display:inline-flex;align-items:center;gap:7px;padding:10px 16px;border:0;border-radius:10px;color:#fff;background:#5347ce;font-size:11px;font-weight:700}.order-update-button:hover{background:#4338ca}.order-totals{padding:13px 22px 20px}.order-totals>div{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:11px 0;border-bottom:1px solid #f1f5f9}.order-totals span{color:#64748b;font-size:10px}.order-totals strong{color:#334155;font-size:11px}.order-totals .order-grand-total{margin-top:3px;padding:16px;border:0;border-radius:12px;background:linear-gradient(135deg,#f0efff,#f5f3ff)}.order-grand-total span{color:#4338ca;font-weight:700}.order-grand-total strong{color:#312e81;font-size:17px}@media(max-width:1199.98px){.order-summary-strip{grid-template-columns:repeat(3,minmax(0,1fr))}.order-summary-strip>div:nth-child(3){border-right:0}.order-summary-strip>div:nth-child(-n+3){border-bottom:1px solid #eef2f7}.order-summary-strip .order-summary-total{grid-column:span 2}}@media(max-width:991.98px){.order-profile-header{align-items:flex-start;flex-direction:column}.order-header-actions{padding-left:53px}.order-status-options{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:575.98px){.order-profile-header h2{font-size:20px}.order-header-actions{width:100%;padding-left:0}.order-header-actions .btn{flex:1;justify-content:center}.order-summary-strip{grid-template-columns:1fr 1fr}.order-summary-strip>div{border-bottom:1px solid #eef2f7}.order-summary-strip>div:nth-child(2n){border-right:0}.order-summary-strip .order-summary-total{grid-column:1/-1;border-bottom:0}.order-contact-grid{grid-template-columns:1fr}.order-contact-grid .order-address{grid-column:auto}.order-status-options{grid-template-columns:1fr 1fr}.order-card-heading{padding:16px}.order-customer-body,.order-status-form{padding:17px}}
    </style>
@endsection
