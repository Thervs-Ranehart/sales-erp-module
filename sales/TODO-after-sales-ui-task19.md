# TODO-after-sales-ui-task19.md

- [ ] Update `sales/resources/views/support/resolution-tracking.blade.php` Actions column:
  - [ ] Wrap all three buttons into a single consistent flex container using Bootstrap (`d-flex flex-nowrap align-items-center gap-2 justify-content-center` where appropriate).
  - [ ] Add `text-nowrap` on button labels (“View details”, “View”, “Edit”, “Delete”).
  - [ ] Ensure Actions cell prevents shrinking and keeps a minimum width.
  - [ ] Ensure first row matches the flex layout used in other rows.
  - [ ] Vertically center cell content (table already has `align-middle`; keep button alignment consistent).
- [ ] Update `sales/resources/views/support/warranty-claims.blade.php` Actions column:
  - [ ] Make every row’s Actions cell use the same flex container pattern.
  - [ ] Add `text-nowrap` to labels to prevent wrapping.
  - [ ] Remove per-button `ms-1` spacing inconsistencies; rely on `gap-2`.
  - [ ] Increase Actions column min-width and prevent column shrink.
- [ ] Sanity check formatting consistency across both pages.
- [x] Run any available frontend build/lint or `npm run dev` (if present) / skip if not necessary.


