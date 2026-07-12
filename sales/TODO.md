# TODO - Support Tickets module (sales)

## Step 1
Inspect existing Support module files (controller + models) and ensure they align with migrations.

## Step 2
Update `app/Http/Controllers/SupportTicketController.php` to implement working endpoints:
- list tickets (filters)
- show ticket detail (relations)
- create ticket (optionally link warranty/service context)
- assign ticket to employee
- record resolution tracking
- record satisfaction feedback

## Step 3
Update `app/Models/SupportTicket.php`:
- match `support_tickets` schema
- add Eloquent relationships to:
  - ticket assignments
  - warranty claims
  - service requests
  - resolution tracking
  - satisfaction monitoring

## Step 4
Update existing `app/Models/WarrantyClaim.php` to match `warranty_claims` schema.

## Step 5
Add new model classes to keep code clean and working:
- `WarrantyRecord`
- `ServiceContract`
- `ServiceRequest`
- `ResolutionTracking`
- `SatisfactionMonitoring`

## Step 6
(After PHP-only work) Update routes/UI wiring if needed (may touch `routes/web.php` later).

## Step 7
Run quick sanity checks (php artisan route:list / phpunit if applicable).

