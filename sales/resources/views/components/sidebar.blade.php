@props(['currentRoute' => null])

@php
use Illuminate\Support\Facades\Route;

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
        ['label' => 'Pricing Rules','route' => 'pricing-rules.index','icon'=> 'tags',],
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
        'route' => 'support.tickets',
        'icon' => 'headset',
        'hasDropdown' => true,
        'children' => [
            ['label' => 'Support Tickets', 'route' => 'support.tickets', 'icon' => 'ticket'],
            ['label' => 'Warranty Records', 'route' => 'support.warranty-records', 'icon' => 'clipboard2-check'],
            ['label' => 'Warranty Claims', 'route' => 'support.warranty-claims', 'icon' => 'file-earmark-text'],
            ['label' => 'Service Requests', 'route' => 'support.service-requests', 'icon' => 'calendar3-range'],
            ['label' => 'Service Contracts', 'route' => 'support.service-contracts', 'icon' => 'file-earmark'],

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
            ['label' => 'Summary', 'route' => 'forecasting.index', 'icon' => 'clipboard-data'],
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

<button type="button" class="btn btn-sm d-md-none" id="sidebarToggle" aria-label="Toggle sidebar" style="position: fixed; top: 12px; left: 12px; z-index: 1100; background:#5347CE; color:#fff; border:1px solid rgba(255,255,255,.25);">
    <i class="bi bi-list"></i>
</button>

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
                @php
                    $itemRouteName = $item['route'];
                    $itemUrl = Route::has($itemRouteName) ? route($itemRouteName) : null;
                @endphp

            @if ($itemUrl)
                    <a href="{{ $itemUrl }}" class="{{ $isActive ? 'active' : '' }}" title="{{ $item['label'] }}">

                    <i class="bi bi-{{ $item['icon'] }}"></i>
                    <span class="nav-label">{{ $item['label'] }}</span>
                    @if ($item['hasDropdown'] ?? false)
                        <i class="bi bi-chevron-down sidebar-drop-icon" data-dropdown-toggle></i>
                    @endif
                </a>

                @endif

                @if (!empty($item['children']))

                    <div class="sub-nav {{ $showChildren ? 'open' : '' }}">
                        @foreach ($item['children'] as $child)
                            @php
                                $childActive = $currentRoute === $child['route'] || str_contains($currentRoute ?? '', $child['route']);
                            @endphp

                            @php
                                $childRouteName = $child['route'];
                                $childUrl = Route::has($childRouteName) ? route($childRouteName) : null;
                            @endphp

                            @if ($childUrl)
                                <a href="{{ $childUrl }}" class="sub-nav-link" title="{{ $child['label'] }}" data-submenu-icon data-active-route="{{ $childActive ? '1' : '0' }}">

                                <i class="bi bi-{{ $child['icon'] }}" data-submenu-icon-inner></i>
                                <span class="nav-label">{{ $child['label'] }}</span>
                            </a>
                            @endif
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

            @php
                $utilRouteName = $item['route'];
                $utilUrl = Route::has($utilRouteName) ? route($utilRouteName) : null;
            @endphp

            @if ($utilUrl)
                <a href="{{ $utilUrl }}" class="{{ $isActive ? 'active' : '' }}" title="{{ $item['label'] }}">

                <i class="bi bi-{{ $item['icon'] }}"></i>
                <span class="nav-label">{{ $item['label'] }}</span>
            </a>

            @endif
        @endforeach


        @php
            $logoutUrl = Route::has('logout') ? route('logout') : url('/');
        @endphp

        <a href="{{ $logoutUrl }}" title="Logout">

            <i class="bi bi-box-arrow-right"></i>
            <span class="nav-label">Logout</span>
        </a>
    </div>
</div>

<style>
    /* Collapsed navigation: retain clickable links but remove all label/chevron layout space. */
    #app-sidebar[data-sidebar-state="collapsed"] .nav-group > a,
    #app-sidebar[data-sidebar-state="collapsed"] > .mt-auto > a {
        justify-content: center;
        gap: 0;
        padding-left: 0;
        padding-right: 0;
    }

    #app-sidebar[data-sidebar-state="collapsed"] .nav-group > a > .nav-label,
    #app-sidebar[data-sidebar-state="collapsed"] > .mt-auto > a > .nav-label,
    #app-sidebar[data-sidebar-state="collapsed"] .sidebar-drop-icon {
        display: none !important;
    }

    #app-sidebar[data-sidebar-state="collapsed"] .nav-group > a > i:first-child,
    #app-sidebar[data-sidebar-state="collapsed"] > .mt-auto > a > i:first-child {
        margin: 0;
        flex: 0 0 auto;
    }

    #app-sidebar[data-sidebar-state="expanded"] .nav-group > a > .nav-label,
    #app-sidebar[data-sidebar-state="expanded"] > .mt-auto > a > .nav-label {
        display: block;
    }

    /* Hide Forecasting/Sales Performance submenu icons while sidebar is collapsed */
    /* Works with the existing layout behavior where sidebar expands on hover. */
    #app-sidebar[data-sidebar-state="collapsed"] .sub-nav-link[data-submenu-icon] i[data-submenu-icon-inner] {
        opacity: 0;
        width: 0;
        transition: opacity .2s ease, width .3s ease;
    }
    #app-sidebar[data-sidebar-state="collapsed"] .sub-nav.open i[data-submenu-icon-inner] {
        opacity: 0;
        width: 0;
    }
    #app-sidebar[data-sidebar-state="collapsed"] .sub-nav-link.active {
        background: transparent;
    }
    #app-sidebar[data-sidebar-state="collapsed"]:hover .sub-nav-link[data-submenu-icon] i[data-submenu-icon-inner] {
        opacity: 1;
        width: auto;
    }

    /* Remove dropdown chevrons entirely while collapsed and only show them when expanded */
    #app-sidebar[data-sidebar-state="collapsed"] .sidebar-drop-icon {
        display: none !important;
    }
    #app-sidebar[data-sidebar-state="collapsed"]:hover .sidebar-drop-icon {
        display: none !important;
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
        max-height: 0;
        opacity: 0;
        padding-top: 0;
        padding-bottom: 0;
    }

    #app-sidebar[data-sidebar-state="expanded"] .sub-nav.open {
        max-height: 420px;
        opacity: 1;
    }

    /* Mobile/tablet: replace hover-only expansion with a tap-to-toggle drawer */
    @media (max-width: 767.98px) {
        /* Disable hover-driven expansion on small screens */
        .sidebar:hover {
            width: 78px;
            height: 100vh;
            max-height: 100vh;
        }

        /* Turn sidebar into an off-canvas drawer */
        #app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1101;
            width: 190px; /* keep labels visible while opened */
            max-height: 100vh;
            transform: translateX(-100%);
            transition: transform .2s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* When open, slide in */
        #app-sidebar.is-open {
            transform: translateX(0);
        }

        /* On mobile, show labels/icons when sidebar is opened */
        #app-sidebar.is-open .brand-text,
        #app-sidebar.is-open .nav-label {
            display: inline !important;
        }

        /* Ensure closed drawer doesn't occupy visible width */
        #app-sidebar:not(.is-open) .brand-text,
        #app-sidebar:not(.is-open) .nav-label {
            display: none !important;
        }

        /* Backdrop */
        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.35);
            z-index: 1100;
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s ease;
        }
        .sidebar-backdrop.is-open {
            opacity: 1;
            pointer-events: auto;
        }
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('app-sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');

        const syncSubmenuActiveState = function () {
            if (!sidebar) return;
            const expanded = sidebar.getAttribute('data-sidebar-state') === 'expanded';

            document.querySelectorAll('.sub-nav-link[data-active-route="1"]').forEach(function (link) {
                link.classList.toggle('active', expanded);
            });
        };

        const ensureBackdrop = function () {
            let backdrop = document.querySelector('.sidebar-backdrop');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.className = 'sidebar-backdrop';
                document.body.appendChild(backdrop);
            }
            return backdrop;
        };

        const isMobile = function () {
            return window.matchMedia && window.matchMedia('(max-width: 767.98px)').matches;
        };

        const openSidebar = function () {
            if (!sidebar) return;
            const backdrop = ensureBackdrop();
            sidebar.classList.add('is-open');
            backdrop.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        };

        const closeSidebar = function () {
            if (!sidebar) return;
            const backdrop = ensureBackdrop();
            sidebar.classList.remove('is-open');
            backdrop.classList.remove('is-open');
            document.body.style.overflow = '';
        };

        if (sidebar) {
            sidebar.addEventListener('mouseenter', function () {
                // Keep desktop behavior unchanged; ignore hover logic on mobile.
                if (isMobile()) return;
                sidebar.setAttribute('data-sidebar-state', 'expanded');
                syncSubmenuActiveState();
            });

            // Do NOT clear submenu active/open state on mouseleave.
            // This prevents the parent submenu (e.g., CRM) from collapsing/losing its state
            // when user navigates to other items inside that parent.
            sidebar.addEventListener('mouseleave', function () {
                if (isMobile()) return;
                sidebar.setAttribute('data-sidebar-state', 'collapsed');
            });
        }

        // Hamburger toggle for mobile
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                if (!isMobile()) {
                    return;
                }

                if (sidebar.classList.contains('is-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }

        // Close when clicking backdrop
        document.addEventListener('click', function (event) {
            if (!isMobile()) return;
            const backdrop = document.querySelector('.sidebar-backdrop');
            if (backdrop && backdrop.classList.contains('is-open') && event.target === backdrop) {
                closeSidebar();
            }
        });

        // Close on escape
        document.addEventListener('keydown', function (event) {
            if (!isMobile()) return;
            if (event.key === 'Escape') {
                closeSidebar();
            }
        });

        // Close if resizing to desktop
        window.addEventListener('resize', function () {
            if (!isMobile()) {
                closeSidebar();
            }
        });

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

