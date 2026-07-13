@props(['currentRoute' => null])

@php
$mainItems = [  
    ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'speedometer2', 'hasDropdown' => false],
    [
    'label' => 'Sales Order Management',
    'route' => 'sales.index',
    'icon' => 'cart-check',
    'hasDropdown' => true,
    'children' => [
        ['label'=>'Sales Orders','route'=>'sales.index','icon'=>'cart'],
        ['label'=>'Quotations','route'=>'quotations.index','icon'=>'file-earmark-text'],
        ['label'=>'Pricing Rules','route'=>'pricing.index','icon'=>'tags'],
        ['label'=>'Invoices','route'=>'invoices.index','icon'=>'receipt'],
    ],
],
    [
    'label' => 'Customer Relationship Management',
    'route' => 'crm.directory',
    'icon' => 'people-fill',
    'hasDropdown' => true,
    'children' => [
        ['label' => 'Customer Directory', 'route' => 'crm.directory', 'icon' => 'people-fill'],
        ['label' => 'Customer Profiles', 'route' => 'crm.profiles', 'icon' => 'person-badge'],
        ['label' => 'Purchase History', 'route' => 'crm.purchase', 'icon' => 'bag-check'],
        ['label' => 'Communication Logs', 'route' => 'crm.logs', 'icon' => 'chat-left-text'],
        ['label' => 'Follow-Ups', 'route' => 'crm.followups', 'icon' => 'calendar-check'],
        ['label' => 'Loyalty Program', 'route' => 'crm.loyalty', 'icon' => 'award'],
        ['label' => 'Customer Segmentation', 'route' => 'crm.segmentation', 'icon' => 'diagram-3'],
    ],
],

    [
        'label' => 'After-Sales Support and Case Management',
        'route' => 'support.index',
        'icon' => 'headset',
        'hasDropdown' => true,
        'children' => [
            ['label' => 'Support Dashboard', 'route' => 'support.index', 'icon' => 'speedometer2'],
            ['label' => 'Support Tickets', 'route' => 'support.tickets', 'icon' => 'ticket'],
            ['label' => 'Warranty Records', 'route' => 'support.warranty-records', 'icon' => 'clipboard2-check'],
            ['label' => 'Warranty Claims', 'route' => 'support.warranty-claims', 'icon' => 'file-earmark-text'],
            ['label' => 'Service Contracts', 'route' => 'support.service-contracts', 'icon' => 'file-earmark'],
            ['label' => 'Service Requests', 'route' => 'support.service-requests', 'icon' => 'list-check'],
            ['label' => 'Resolution Tracking', 'route' => 'support.resolution-tracking', 'icon' => 'diagram-3'],
            ['label' => 'Customer Satisfaction', 'route' => 'support.customer-satisfaction', 'icon' => 'star'],
        ],
    ],
    [
        'label' => 'Sales Performance Reporting and Forecasting',
        'route' => 'forecasting.index',
        'icon' => 'graph-up-arrow',
        'hasDropdown' => true,
        'children' => [
            ['label' => 'Sales Reports', 'route' => 'forecasting.reports', 'icon' => 'bar-chart-line'],
            ['label' => 'Target vs. Actual Performance', 'route' => 'forecasting.performance', 'icon' => 'clipboard-data'],
            ['label' => 'Forecasting', 'route' => 'forecasting.forecast', 'icon' => 'graph-up'],
            ['label' => 'Recommendations', 'route' => 'forecasting.recommendations', 'icon' => 'lightbulb'],
        ],
    ],
];

$utilityItems = [
    ['label' => 'Notifications', 'route' => 'notifications.index', 'icon' => 'bell'],
    ['label' => 'Profile', 'route' => 'profile.index', 'icon' => 'person-circle'],
];
@endphp

<div class="sidebar d-flex flex-column" id="app-sidebar" data-sidebar-state="collapsed">
    <h4 class="brand">
        <span class="brand-mark">CL</span>
        <span class="brand-text">COMPANY LOGO</span>
    </h4>


    <div>
        @foreach ($mainItems as $item)
            @php
                $isActive = $currentRoute === $item['route'] || str_contains($currentRoute ?? '', $item['route']);
                $showChildren = false;

                if (!empty($item['children'])) {
                    foreach ($item['children'] as $child) {
                        if ($currentRoute === $child['route'] || str_contains($currentRoute ?? '', $child['route'])) {
                            $showChildren = true;
                            break;
                        }
                    }
                }

                // Expand parent when any child route is active.
                $isActive = $isActive || $showChildren;

                // Auto-open submenu when viewing children (expanded + open state)
                // so the parent is highlighted correctly.
                if (!empty($item['children']) && $showChildren) {
                    // no-op: state handled by $showChildren and the 'open' class below
                }
            @endphp

            <div class="nav-group">
                <a href="{{ route($item['route']) }}" class="{{ $isActive ? 'active' : '' }}" title="{{ $item['label'] }}">
                    <i class="bi bi-{{ $item['icon'] }}"></i>
                    <span class="nav-label">{{ $item['label'] }}</span>
                    @if ($item['hasDropdown'] ?? false)
                        <i class="bi bi-chevron-down sidebar-drop-icon" data-dropdown-toggle></i>
                    @endif
                </a>

                @if (!empty($item['children']))
                    <div class="sub-nav {{ $showChildren ? 'open' : '' }}">
                        @foreach ($item['children'] as $child)
                            @php
                                $childActive = $currentRoute === $child['route'] || str_contains($currentRoute ?? '', $child['route']);
                            @endphp

                            <a href="{{ route($child['route']) }}" class="sub-nav-link" title="{{ $child['label'] }}" data-submenu-icon data-active-route="{{ $childActive ? '1' : '0' }}">
                                <i class="bi bi-{{ $child['icon'] }}" data-submenu-icon-inner></i>
                                <span class="nav-label">{{ $child['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-auto pt-3 border-top border-light border-opacity-25">
        @foreach ($utilityItems as $item)
            @php
                $isActive = $currentRoute === $item['route'] || str_contains($currentRoute ?? '', $item['route']);
            @endphp

            <a href="{{ route($item['route']) }}" class="{{ $isActive ? 'active' : '' }}" title="{{ $item['label'] }}">
                <i class="bi bi-{{ $item['icon'] }}"></i>
                <span class="nav-label">{{ $item['label'] }}</span>
            </a>
        @endforeach

        <a href="{{ route('logout') }}" title="Logout">
            <i class="bi bi-box-arrow-right"></i>
            <span class="nav-label">Logout</span>
        </a>
    </div>
</div>

<style>
    /* Hide Forecasting/Sales Performance submenu icons while sidebar is collapsed */
    /* Works with the existing layout behavior where sidebar expands on hover. */
    #app-sidebar[data-sidebar-state="collapsed"] .sub-nav-link[data-submenu-icon] i[data-submenu-icon-inner] {
        display: none;
    }
    #app-sidebar[data-sidebar-state="collapsed"] .sub-nav.open i[data-submenu-icon-inner] {
        display: none;
    }
    #app-sidebar[data-sidebar-state="collapsed"] .sub-nav-link.active {
        background: transparent;
    }
    #app-sidebar[data-sidebar-state="collapsed"]:hover .sub-nav-link[data-submenu-icon] i[data-submenu-icon-inner] {
        display: inline-block;
    }

    /* Remove dropdown chevrons entirely while collapsed and only show them when expanded */
    #app-sidebar[data-sidebar-state="collapsed"] .sidebar-drop-icon {
        display: none;
    }
    #app-sidebar[data-sidebar-state="collapsed"]:hover .sidebar-drop-icon {
        display: inline-block;
    }

    /* When collapsed, keep submenu indentation so it doesn't line up with the parent icon */
    #app-sidebar[data-sidebar-state="collapsed"] .sub-nav {
        padding-left: 20px;
    }

    /* In collapsed state, ensure main/utility icons are centered */
    #app-sidebar[data-sidebar-state="collapsed"] > div > .nav-group > a,
    #app-sidebar[data-sidebar-state="collapsed"] > .mt-auto a {
        justify-content: center;
    }

    #app-sidebar[data-sidebar-state="collapsed"] .nav-group > a {
        justify-content: center;
    }

    #app-sidebar[data-sidebar-state="collapsed"] .mt-auto a {
        justify-content: center;
    }

    /* Keep active submenu visible even while collapsed */
    #app-sidebar[data-sidebar-state="collapsed"] .sub-nav.open {
        display: flex !important;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('app-sidebar');

        const syncSubmenuActiveState = function () {
            const expanded = sidebar.getAttribute('data-sidebar-state') === 'expanded';

            document.querySelectorAll('.sub-nav-link[data-active-route="1"]').forEach(function (link) {
                link.classList.toggle('active', expanded);
            });
        };

        if (sidebar) {
            sidebar.addEventListener('mouseenter', function () {
                sidebar.setAttribute('data-sidebar-state', 'expanded');
                syncSubmenuActiveState();
            });

            // Do NOT clear submenu active/open state on mouseleave.
            // This prevents the parent submenu (e.g., CRM) from collapsing/losing its state
            // when user navigates to other items inside that parent.
            sidebar.addEventListener('mouseleave', function () {
                sidebar.setAttribute('data-sidebar-state', 'collapsed');
            });
        }


        document.querySelectorAll('.sidebar-drop-icon[data-dropdown-toggle]').forEach(function (icon) {
            icon.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                const group = this.closest('.nav-group');
                const subNav = group ? group.querySelector('.sub-nav') : null;

                if (!subNav) {
                    return;
                }

                subNav.classList.toggle('open');
                this.classList.toggle('rotated');
            });
        });

        syncSubmenuActiveState();
    });
</script>
