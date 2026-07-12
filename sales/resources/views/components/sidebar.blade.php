@props(['currentRoute' => null])

@php
$mainItems = [  
    ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'speedometer2', 'hasDropdown' => false],
    ['label' => 'Sales Order Management', 'route' => 'sales.index', 'icon' => 'cart-check', 'hasDropdown' => true],
    ['label' => 'Customer Relationship Management', 'route' => 'crm.index', 'icon' => 'people-fill', 'hasDropdown' => true],
    ['label' => 'After-Sales Support and Case Management', 'route' => 'support.index', 'icon' => 'headset', 'hasDropdown' => true],
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

                $isActive = $isActive || $showChildren;
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

            sidebar.addEventListener('mouseleave', function () {
                sidebar.setAttribute('data-sidebar-state', 'collapsed');
                document.querySelectorAll('.sub-nav-link.active').forEach(function (link) {
                    link.classList.remove('active');
                });
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
