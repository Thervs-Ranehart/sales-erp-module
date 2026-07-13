# TODO - Task 16 After-Sales Module Layout + Sidebar (UI only)

## Step 1: Stabilize layout + sidebar/content behavior
- [x] Update `sales/resources/views/layouts/app.blade.php` to enforce:
  - sidebar fully visible, proper width, no text clipping
  - content starts after sidebar and never overlaps

## Step 2: Remove developer placeholder text
- [x] Updated: `support/index.blade.php`, `support/tickets.blade.php`, `support/warranty-records.blade.php`, `support/service-contracts.blade.php`, `support/warranty-claims.blade.php`, `support/customer-satisfaction.blade.php`

- [x] Remaining placeholder/developer text still present will be removed in:

  - `sales/resources/views/support/index.blade.php`
  - `sales/resources/views/support/tickets.blade.php`
  - `sales/resources/views/support/warranty-records.blade.php`
  - `sales/resources/views/support/warranty-claims.blade.php`
  - `sales/resources/views/support/service-contracts.blade.php`
  - `sales/resources/views/support/service-requests.blade.php`
  - `sales/resources/views/support/resolution-tracking.blade.php`
  - `sales/resources/views/support/customer-satisfaction.blade.php`

## Step 3: Spacing + responsiveness improvements
- [ ] Standardize filter spacing/wrapping and action button gaps on all 8 pages.
- [ ] Improve table cell/thead spacing and ensure horizontal scroll only when needed.

## Step 4: Quick verification checklist
- [ ] Sidebar behaves like Dashboard and remains functional at smaller widths.
- [ ] No overlap: content never covers sidebar.
- [ ] Buttons don’t wrap unexpectedly; filters wrap neatly.
- [ ] No standalone full-width layout created.

