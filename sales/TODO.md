# sales/TODO.md (CRM final wiring verification)

- [x] customer-logs.blade.php: remove `@if(false && Route::has(...))` guard and wire delete button to `crm.logs.destroy` with `@csrf` + `@method('DELETE')`.
- [x] customer-followups.blade.php: add minimal delete form/button to existing actions area wired to `crm.followups.destroy` with `@csrf` + `@method('DELETE')`.
- [x] customer-segmentation.blade.php: wire View button to `crm.profiles` with `customer_id`.
- [x] customer-loyalty.blade.php: wire View button to `crm.profiles` with `customer_id` when available.
- [ ] Run `php artisan route:list`
- [ ] Run `php artisan view:clear`
- [ ] Final CRM completion report

