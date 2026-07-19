# TODO - Warranty Claims Action buttons + Modals

- [ ] Inspect existing Warranty Claims page, modal markup, and Support Tickets modal workflow for reuse
- [ ] Update Warranty Claims table action buttons styling to match Support Tickets
- [ ] Make actions column responsive (no overflow on small screens)
- [ ] Implement “Review” button:
  - [ ] Open Bootstrap modal (no navigation, no reload)
  - [ ] Fetch selected warranty claim by claim_id (AJAX)
  - [ ] Populate modal with all claim details
  - [ ] Allow user to review/update claim fields if applicable
- [ ] Implement “Update Status” button:
  - [ ] Open Bootstrap modal
  - [ ] Dropdown of available claim statuses
  - [ ] Save selected status to DB (AJAX)
  - [ ] Close modal after save
  - [ ] Update only affected row’s status badge immediately
  - [ ] Show Bootstrap success notification
- [ ] Add/adjust backend endpoints (mirroring SupportTicketController):
  - [ ] WarrantyClaim show/details endpoint for modal population
  - [ ] WarrantyClaim updateStatus endpoint for status save
- [ ] Wire routes in routes/web.php
- [ ] Add any required JS to warranty-claims.blade.php (reusing Support Tickets patterns)
- [ ] Verify:
  - [ ] Review opens modal and displays correct claim data
  - [ ] Update Status saves and refreshes badge immediately
  - [ ] No JS/Laravel errors; no 404/419/500 responses

