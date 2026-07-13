# TODO - UI-only revert (remove database/backend dev parts)

- [ ] Update ForecastingController to stop using RevenueTrendService and pass static monthlyRevenue.
- [ ] Remove/disable RevenueTrendService (delete file or leave unused; prefer delete).
- [ ] Remove/disable database migrations (delete migration PHP files or move them out of migrations folder).
- [ ] Remove/disable database seeders (delete DatabaseSeeder + CheckData).
- [ ] Remove DB models used by backend (delete models except User.php if needed for UI/auth).
- [ ] Ensure routes and views still compile (no missing controller/service references).

