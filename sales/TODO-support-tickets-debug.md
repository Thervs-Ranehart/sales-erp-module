# TODO - Support Tickets Action Buttons Debug/Repair

## Step 1: Stabilize Assign Save action
- [ ] Add a real Save button inside `sales/resources/views/support/tickets-assign-modal.blade.php` with a stable id.
- [ ] Update `sales/resources/views/support/tickets.blade.php` JS to bind directly to that id.

## Step 2: Replace blocking alerts with Bootstrap notification
- [ ] Implement a small Bootstrap toast/alert notification container in `tickets.blade.php`.
- [ ] Replace `alert(...)` for Assign/Status success/failure with the notification.

## Step 3: Verify AJAX flow + UI update correctness
- [ ] Add extra console logs and ensure `res.ok` + JSON parsing.
- [ ] Update the assigned employee cell in the affected row after successful POST.

## Step 4: Ensure View modal populates required fields
- [ ] Confirm IDs exist in `tickets-details-modal.blade.php` for required fields.
- [ ] Add missing fields/IDs only if needed (no module rewrite).

## Step 5: Manual verification checklist
- [ ] View opens modal immediately and is fully populated.
- [ ] Assign loads employees, Save POST succeeds, modal closes, row updates, toast shows.
- [ ] Status modal updates status badge immediately, modal closes, toast shows.
- [ ] No console JS errors, no 404/419/500, no page reload.

