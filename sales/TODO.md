# Task: Create Separate Agents Table

## Steps
- [x] 1. Create migration for `agents` table (agent_id, first_name, last_name, email, phone, department, status, created_at, updated_at)
- [x] 2. Create `App\Models\Agent` model
- [x] 3. Create migration to add `agent_id` (nullable FK) to `communication_logs` table
- [x] 4. Update `CommunicationLog` model to add `agent()` relationship
- [x] 5. Update `CustomerFollowUpsController` to use `Agent` model instead of `Employee`
- [x] 6. Update `DashboardController` to query `Agent` model for follow-up data
- [x] 7. Update `dashboard/index.blade.php` table to reflect Agent model data

