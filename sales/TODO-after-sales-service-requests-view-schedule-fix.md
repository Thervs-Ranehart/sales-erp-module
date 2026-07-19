# TODO: Service Requests View & Schedule modal fix

## Step 1 — Understand current wiring
- Confirm both View and Schedule buttons target the same modal: `#serviceRequestScheduleModal`.
- Verify current JS uses `data-mode` to toggle title/footer/action.

## Step 2 — Fix modal title + footer actions per mode
- Implement separate UI for `view` vs `schedule` (different title + different primary footer button text/action).
- Ensure footer primary action is not “Schedule (placeholder)” for view mode.

## Step 3 — Prefer clean behavior; remove duplicate if needed
- If implementing separate behavior cleanly requires duplication (e.g., two modals), decide whether to add minimal code or remove duplicates.

## Step 4 — Testing
- Manual browser test on `/support/service-requests`:
  - Click **Schedule** → modal shows schedule title and schedule primary button.
  - Click **View** → modal shows view title and close-only (or “Close” primary) behavior.
- Verify no other support pages/modal are affected.

## Step 5 — Report
- List modified files.
- Provide testing steps to verify fix.

