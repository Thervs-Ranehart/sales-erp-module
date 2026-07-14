@php($filterMode = $filterMode ?? 'reports')
<section class="mt-4">
    <!-- Filter Drawer (Report Filters) -->
    <div id="rf-filter-overlay" class="rf-overlay" aria-hidden="true">
        <aside
            id="rf-filter-drawer"
            class="rf-drawer"
            role="dialog"
            aria-modal="true"
            aria-labelledby="rf-filter-title"
        >
            <div class="rf-drawer-header">
                <div>
                    <div class="rf-filter-icon">
                        <i class="bi bi-funnel"></i>
                    </div>
                </div>

                <div class="rf-drawer-header-text">
                    <h2 id="rf-filter-title" class="rf-drawer-title">{{ $filterMode === 'performance' ? 'Performance Filters' : 'Sales Report Filters' }}</h2>
                    <p class="rf-drawer-desc">{{ $filterMode === 'performance' ? 'Focus target achievement by period, contributor, and status.' : 'Customize revenue reporting by period, product, region, and representative.' }}</p>
                </div>

                <button id="rf-close-filter" class="rf-close" type="button" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="rf-drawer-body">
                <div class="rf-field">
                    <label class="rf-label" for="rf-date-range">Date Range</label>
                    <select id="rf-date-range" name="period" class="rf-select">
                        @foreach(['this-month' => 'This month', 'last-month' => 'Last month', 'quarter' => 'Quarter', 'year' => 'Year', 'custom' => 'Custom'] as $value => $label)<option value="{{ $value }}" @selected(request('period', 'year') === $value)>{{ $label }}</option>@endforeach
                    </select>
                </div>

                <div class="rf-field" id="rf-custom-start-wrap" style="display:none;">
                    <label class="rf-label" for="rf-start-date">Start Date</label>
                    <input id="rf-start-date" name="start_date" value="{{ request('start_date') }}" type="date" class="rf-select" />
                </div>

                <div class="rf-field" id="rf-custom-end-wrap" style="display:none;">
                    <label class="rf-label" for="rf-end-date">End Date</label>
                    <input id="rf-end-date" name="end_date" value="{{ request('end_date') }}" type="date" class="rf-select" />
                </div>

                <div class="rf-field">
                    <label class="rf-label" for="rf-region">Region</label>
                    <select id="rf-region" name="region" class="rf-select">
                        @foreach(['all' => 'All regions', 'ncr' => 'National Capital Region (NCR)', 'visayas' => 'Visayas', 'mindanao' => 'Mindanao'] as $value => $label)<option value="{{ $value }}" @selected(request('region', 'all') === $value)>{{ $label }}</option>@endforeach
                    </select>
                </div>

                <div class="rf-field">
                    <label class="rf-label" for="rf-product-category">Product Categories</label>
                    <select id="rf-product-category" name="product" class="rf-select">
                        @foreach(['all' => 'All product categories', 'category-a' => 'Category A', 'category-b' => 'Category B', 'category-c' => 'Category C'] as $value => $label)<option value="{{ $value }}" @selected(request('product', 'all') === $value)>{{ $label }}</option>@endforeach
                    </select>
                </div>

                <div class="rf-field">
                    <label class="rf-label" for="rf-sales-representative">Sales Representative</label>
                    <select id="rf-sales-representative" name="representative" class="rf-select">
                        @foreach(['all' => 'All representatives', 'rep-1' => 'Representative 1', 'rep-2' => 'Representative 2', 'rep-3' => 'Representative 3'] as $value => $label)<option value="{{ $value }}" @selected(request('representative', 'all') === $value)>{{ $label }}</option>@endforeach
                    </select>
                </div>

                @if($filterMode === 'performance')
                <div class="rf-field">
                    <label class="rf-label" for="rf-performance-status">Performance Status</label>
                    <select id="rf-performance-status" name="status" class="rf-select">
                        @foreach(['all' => 'All', 'exceeded' => 'Exceeded', 'on-target' => 'On Target', 'below-target' => 'Below Target'] as $value => $label)<option value="{{ $value }}" @selected(request('status', 'all') === $value)>{{ $label }}</option>@endforeach
                    </select>
                </div>
                @endif




                <div class="rf-actions">
                    <button type="button" class="rf-btn-secondary" id="rf-reset">Reset</button>
                    <button type="button" class="rf-btn-primary" id="rf-apply">Apply Filters</button>
                </div>
            </div>
        </aside>
    </div>

    <style>
        .rf-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            display: flex;
            justify-content: flex-end;
            z-index: 1050;
            opacity: 0;
            pointer-events: none;
            transition: opacity 300ms ease;
        }

        .rf-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        .rf-drawer {
            width: min(420px, calc(100vw - 24px));
            height: calc(100vh - 32px);
            margin: 16px;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(0,0,0,0.22);
            transform: translateX(110%);
            transition: transform 380ms cubic-bezier(0.22, 1, 0.36, 1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .rf-overlay.open .rf-drawer {
            transform: translateX(0);
        }

        .rf-drawer-header {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            padding: 18px 18px 12px 18px;
            border-bottom: 1px solid rgba(17, 24, 39, 0.08);
        }

        .rf-filter-icon {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: rgba(83, 71, 206, 0.12);
            color: #5347CE;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .rf-drawer-header-text {
            flex: 1;
            min-width: 0;
        }

        .rf-drawer-title {
            font-size: 18px;
            font-weight: 800;
            margin: 0;
            color: #0f172a;
        }

        .rf-drawer-desc {
            margin: 6px 0 0 0;
            font-size: 13px;
            line-height: 1.35;
            color: rgba(15, 23, 42, 0.7);
        }

        .rf-close {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(15, 23, 42, 0.8);
            cursor: pointer;
            flex-shrink: 0;
        }

        .rf-close:hover {
            background: rgba(15, 23, 42, 0.04);
        }

        .rf-drawer-body {
            padding: 14px 18px 18px 18px;
            overflow: auto;
        }

        .rf-field {
            margin-bottom: 14px;
        }

        .rf-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: rgba(15, 23, 42, 0.75);
            margin-bottom: 8px;
        }

        .rf-select {
            width: 100%;
            height: 42px;
            border-radius: 14px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            background: #fff;
            padding: 0 14px;
            outline: none;
            color: rgba(15, 23, 42, 0.95);
        }

        .rf-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .rf-btn-primary,
        .rf-btn-secondary {
            border: 0;
            height: 40px;
            padding: 0 14px;
            border-radius: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: transform 120ms ease, background 160ms ease;
        }

        .rf-btn-primary {
            background: #5347CE;
            color: #fff;
        }

        .rf-btn-primary:hover {
            background: #4a3fd0;
            transform: translateY(-1px);
        }

        .rf-btn-secondary {
            background: rgba(83, 71, 206, 0.10);
            color: #5347CE;
            border: 1px solid rgba(83, 71, 206, 0.18);
        }

        .rf-btn-secondary:hover {
            background: rgba(83, 71, 206, 0.14);
            transform: translateY(-1px);
        }
    </style>

    <script>
        (function () {
            const openBtn = document.getElementById('rf-open-filter');
            const overlay = document.getElementById('rf-filter-overlay');
            const drawer = document.getElementById('rf-filter-drawer');
            const closeBtn = document.getElementById('rf-close-filter');
            const resetBtn = document.getElementById('rf-reset');
            const applyBtn = document.getElementById('rf-apply');

            if (!openBtn || !overlay || !drawer || !closeBtn) return;

            let lastFocusedElement = null;

            const open = () => {
                lastFocusedElement = document.activeElement;
                overlay.classList.add('open');
                overlay.setAttribute('aria-hidden', 'false');
                openBtn.setAttribute('aria-expanded', 'true');
                closeBtn.focus();
            };
            const close = () => {
                overlay.classList.remove('open');
                overlay.setAttribute('aria-hidden', 'true');
                openBtn.setAttribute('aria-expanded', 'false');
                if (lastFocusedElement) lastFocusedElement.focus();
            };

            openBtn.addEventListener('click', open);
            closeBtn.addEventListener('click', close);

            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) close();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') close();
            });

            if (resetBtn) {
                resetBtn.addEventListener('click', function () {
                    window.location.assign(window.location.pathname);
                });
            }



            const dateRangeSelect = document.getElementById('rf-date-range');
            const startDateInput = document.getElementById('rf-start-date');
            const endDateInput = document.getElementById('rf-end-date');
            const customStartWrap = document.getElementById('rf-custom-start-wrap');
            const customEndWrap = document.getElementById('rf-custom-end-wrap');

            if (dateRangeSelect && startDateInput && endDateInput && customStartWrap && customEndWrap) {

                const syncCustomRangeVisibility = function () {
                    const custom = dateRangeSelect.value === 'custom';

                    if (customStartWrap) customStartWrap.style.display = custom ? 'block' : 'none';
                    if (customEndWrap) customEndWrap.style.display = custom ? 'block' : 'none';

                    startDateInput.disabled = !custom;
                    endDateInput.disabled = !custom;

                    // Keep values only when custom is selected
                    if (!custom) {
                        startDateInput.value = '';
                        endDateInput.value = '';
                    }
                };

                dateRangeSelect.addEventListener('change', syncCustomRangeVisibility);
                syncCustomRangeVisibility();
            }


            if (applyBtn) {
                applyBtn.addEventListener('click', function () {
                    const parameters = new URLSearchParams();
                    drawer.querySelectorAll('[name]').forEach(function (field) {
                        if (field.value && field.value !== 'all') parameters.set(field.name, field.value);
                    });
                    window.location.assign(window.location.pathname + (parameters.toString() ? '?' + parameters.toString() : ''));
                });
            }
        })();
    </script>
</section>
