# TODO

## Forecasting submenu refactor
- [ ] Create 4 independent Blade pages under `resources/views/forecasting/`:
  - [x] `reports.blade.php`
  - [x] `performance.blade.php`
  - [x] `forecast.blade.php`
  - [x] `recommendations.blade.php`
- [x] Each page must include its own hero/intro and unique content section.
- [ ] Update `app/Http/Controllers/ForecastingController.php` methods:
  - [x] `reports()` -> `forecasting.reports`
  - [x] `performance()` -> `forecasting.performance`
  - [x] `forecast()` -> `forecasting.forecast`
  - [x] `recommendations()` -> `forecasting.recommendations`
- [ ] Verify `routes/web.php` named routes exist (no changes unless needed).
- [ ] Confirm sidebar links remain unchanged.


