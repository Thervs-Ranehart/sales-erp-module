# TODO-after-performance-ui-redesign

- [ ] Update `ForecastingController::performance()` to pass placeholder datasets for KPIs, charts, table, and insights.
- [ ] Redesign `resources/views/forecasting/performance.blade.php`:
  - [ ] Hero export dropdown: “Export Report ▼” with PDF/Excel options.
  - [ ] Replace KPI cards with the 6 required KPI definitions.
  - [ ] Keep existing navigation tabs and replace filter button with the filter drawer trigger.
  - [ ] Add grouped vertical bar chart: Target vs. Actual Revenue.
  - [ ] Add line chart: Achievement Trend (%).
  - [ ] Add “Performance Analysis” section using the existing horizontal-bar chart components.
  - [ ] Add Target vs Actual responsive table with client-side sorting.
  - [ ] Add “Performance Insights” small panel (no recommendations).
- [ ] Sanity check: ensure filter drawer JS works (button id `rf-open-filter`).
- [ ] Sanity check: ensure Chart.js initialization does not duplicate/overlap canvases.

