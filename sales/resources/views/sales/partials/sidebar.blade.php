

<div class="sidebar">

    <h4>SQMS</h4>

    <a href="{{ route('dashboard') }}"
       class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        Dashboard
    </a>

    <div class="menu-title">Sales Order Management</div>

    {{-- SALES ORDERS --}}
    <a href="{{ route('sales.index') }}"
       class="{{ request()->routeIs('sales.*') ? 'active' : '' }}">
        <i class="bi bi-cart-check"></i>
        Sales Orders
    </a>


    {{-- QUOTATIONS --}}
    <a href="{{ route('quotations.index') }}"
       class="{{ request()->routeIs('quotations.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i>
        Quotations
    </a>

    {{-- Only show when inside Quotations --}}
    @if(request()->routeIs('quotations.*'))
        <a href="{{ route('quotations.create') }}"
           class="sub-menu {{ request()->routeIs('quotations.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i>
            New Quotation
        </a>
    @endif


    {{-- INVOICES --}}
    <a href="{{ route('invoices.index') }}"
       class="{{ request()->routeIs('invoices.*') ? 'active' : '' }}">
        <i class="bi bi-receipt"></i>
        Invoices
    </a>

    {{-- Only show when inside Invoices --}}
    @if(request()->routeIs('invoices.*'))
        <a href="{{ route('invoices.create') }}"
           class="sub-menu {{ request()->routeIs('invoices.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i>
            Create Invoice
        </a>
    @endif


    {{-- PRICING RULES --}}
    <a href="{{ route('pricing.index') }}"
       class="{{ request()->routeIs('pricing.*') ? 'active' : '' }}">
        <i class="bi bi-tags"></i>
        Pricing Rules
    </a>

    {{-- Only show when inside Pricing Rules --}}
    @if(request()->routeIs('pricing.*'))
        <a href="{{ route('pricing.create') }}"
           class="sub-menu {{ request()->routeIs('pricing.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i>
            Create Pricing Rule
        </a>
    @endif

</div>