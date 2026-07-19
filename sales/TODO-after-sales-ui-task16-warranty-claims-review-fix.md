# TODO - Warranty Claims Review button fix

## Step 1 - Identify problem spots
- [x] Found warranty claim review modal is currently UI-only placeholder
- [x] Found `loadWarrantyClaimIntoModal(claimId)` exists in `support/warranty-claims.blade.php`
- [x] Found Review buttons are rendered with class `.js-warranty-claim-review` and `data-claim-id`, but no click listener calls `loadWarrantyClaimIntoModal()`

## Step 2 - Implement JS wiring
- [x] Attach click listeners to `.js-warranty-claim-review` buttons
- [x] On click, read `data-claim-id` and call `loadWarrantyClaimIntoModal(claimId)`
- [x] Remove/stop any hardcoded placeholder behavior so modal is populated from AJAX response


## Step 3 - Validate status workflow compatibility
- [ ] Ensure existing “Update Status” handler continues to work (unchanged logic)


## Step 4 - Test
- [ ] Manual browser testing: open Warranty Claims page, click each Review button, verify modal contents update per claim
- [ ] Verify status update still updates correct row badge

