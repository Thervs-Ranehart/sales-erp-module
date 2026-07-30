//Sales ERP System

A Laravel-based Sales ERP system designed to manage core sales operations such as dashboard views, sales orders, customer relationships, after-sales support, forecasting, notifications, and user profile management.


//Overview

This system provides a modular ERP-style interface for managing business workflows in a single web application. It currently includes:

Dashboard
Sales Order Management
Customer Relationship Management
After-Sales Support and Case Management
Sales Performance Reporting and Forecasting
Notifications
Profile


//Tech Stack

PHP 8.3
Laravel 13
Blade Templates
Tailwind CSS
Vite
Bootstrap Icons


//Project Structure

app/Http/Controllers – route handlers for each module
app/Models – domain models for sales, customers, forecasting, tickets, and notifications
resources/views – Blade templates and UI components
routes/web.php – application routes
database/migrations – database schema definitions
tests – feature and unit tests


//Current Status

The project is currently in an early-to-mid development stage. The UI has been structured into reusable layout and component patterns, and the core ERP modules have database-backed workflows and feature tests. The frontend production build currently succeeds.

The application is not production-ready yet. Authentication is only applied to a small number of forecasting routes, authorization is incomplete, and several financial and historical-record workflows need stronger concurrency and deletion safeguards.

The repository currently contains 41 Pest test definitions across 10 test files. These tests must be rerun before merging security or workflow changes. The committed test summary files only describe 4-5 tests and should not be treated as the current full-suite result.


//Required Fixes for Future Collaborators

Work through this list in priority order. Add or update feature tests with every fix.


Critical - Authentication and Authorization

- [ ] Protect all dashboard, CRM, sales, invoice, support, notification, profile, and forecasting routes with `EnsureEmployeeSession`. At present, only three forecasting routes use it.
- [ ] Keep only the login page and login submission publicly accessible. Any future customer-facing routes must be placed in an explicitly public route group.
- [ ] Add role-based authorization using Laravel policies, gates, or dedicated middleware. Define permissions for Sales, Finance, Warehouse, Support, Managers, and Administrators.
- [ ] Protect create, update, delete, archive, restore, approval, shipment, credit-note, attachment, export, and forecasting operations individually. Authentication alone is not sufficient.
- [ ] Remove all fallbacks that use the first employee in the database when `employee_id` is missing from the session. An unauthenticated request must never be attributed to an arbitrary employee.
- [ ] Change logout from `GET /logout` to a CSRF-protected POST request. Invalidate the session and regenerate its CSRF token after logout.
- [ ] Add feature tests proving that unauthenticated users are redirected and users with the wrong role receive HTTP 403 responses.

Primary files:

- `routes/web.php`
- `app/Http/Middleware/EnsureEmployeeSession.php`
- `app/Http/Controllers/Auth/LoginController.php`
- `app/Http/Requests/`
- Controllers that currently use `Employee::query()->value('employee_id')`


Critical - Accounts and Login Security

- [ ] Remove the shared password `123` from `database/seeders/EmployeeSeeder.php`.
- [ ] Generate random temporary passwords or accept development credentials through environment variables. Require password changes before real deployment.
- [ ] Fix role naming. The seed data uses values such as `Sales Manager` and `Finance Manager`, while current approval checks only recognize exact values such as `manager`, `admin`, and `supervisor`.
- [ ] Centralize role definitions instead of repeating string arrays in controllers.
- [ ] Add IP-and-username login rate limiting. Account lockout alone allows an attacker to repeatedly lock known accounts and does not adequately throttle unknown usernames.
- [ ] Implement the Remember Me and Forgot Password controls shown on the login page, or remove them until those workflows exist.
- [ ] Remove the fixed `APP_KEY` from `.env.example`; every installation must generate its own key.
- [ ] For production, set `APP_ENV=production`, set `APP_DEBUG=false`, use HTTPS-only secure cookies, and consider encrypted sessions.


High - Financial and Inventory Integrity

- [ ] Lock and reload the sales order, its items, and existing invoice quantities inside the invoice transaction. The current pre-transaction calculation can allow simultaneous requests to over-invoice an order.
- [ ] Lock invoice and credit-note records while calculating refundable quantities. Simultaneous requests must not be able to over-refund or restore excess inventory.
- [ ] Add concurrency-focused tests for partial invoices, stock deductions, credit notes, loyalty points, and cancellation reversals.
- [ ] Confirm that every financial or inventory mutation creates an immutable audit record with the authenticated employee ID.
- [ ] Do not allow a financial action to proceed when no authenticated employee is available.

Primary files:

- `app/Http/Controllers/InvoiceController.php`
- `app/Http/Controllers/CreditNoteController.php`
- `app/Http/Controllers/ShipmentController.php`
- `app/Services/LoyaltyService.php`
- `app/Models/SalesAuditLog.php`


High - Historical Records and Deletion

- [ ] Replace destructive deletion with cancellation or archival once an order, quotation, invoice, customer, or other record has downstream history.
- [ ] Prevent sales-order deletion when invoices, shipments, approvals, audit logs, or related records exist.
- [ ] Prevent quotation deletion after it has been converted to a sales order.
- [ ] Prefer customer archival over deletion when the customer has orders, invoices, tickets, loyalty transactions, communications, or campaign history.
- [ ] Review all foreign-key delete behavior and explicitly choose `restrict`, `nullOnDelete`, or `cascadeOnDelete` according to the business rule.
- [ ] Add tests for every permitted and rejected deletion path. Database constraint exceptions must not be exposed as server errors.

Primary files:

- `app/Models/SalesOrder.php`
- `app/Models/Quotation.php`
- `app/Http/Controllers/SalesOrderController.php`
- `app/Http/Controllers/QuotationController.php`
- `app/Http/Controllers/Crm/CustomerDirectoryController.php`
- `database/migrations/`


High - Support Attachments

- [ ] Store support attachments on a private disk instead of the public disk.
- [ ] Add an authenticated and authorized download endpoint.
- [ ] Check that the current employee may access the attachment's ticket before download or deletion.
- [ ] Remove a stored file if its database transaction fails so orphaned files are not left behind.
- [ ] Keep file size, extension, and MIME-type validation, and add upload/download authorization tests.

Primary file:

- `app/Http/Controllers/SupportAttachmentController.php`


Medium - Performance and Frontend Reliability

- [ ] Paginate sales orders, quotations, invoices, pricing rules, and other growing lists instead of loading all records with `get()`.
- [ ] Review dashboard and export queries for large-dataset memory usage and N+1 queries.
- [ ] Bundle Bootstrap, Bootstrap Icons, Chart.js, and fonts through the frontend build instead of loading multiple CDN copies in Blade templates.
- [ ] Remove duplicate Bootstrap and Chart.js script imports from individual pages and components.
- [ ] Replace or remove unused Laravel starter content such as `resources/views/welcome.blade.php` if it is not part of the application.


Verification Required Before Merge

Run these checks after each group of fixes:

```bash
php artisan route:list --except-vendor
php artisan view:clear
php artisan test --compact
vendor/bin/pint --format agent
npm run build
npm audit --omit=dev
```

For authentication work, manually verify:

- A guest cannot open or mutate any internal ERP record.
- A valid employee can log in and log out.
- Logging out invalidates the old session.
- Each role can only access its intended modules and actions.
- A disabled or locked employee cannot authenticate.

For sales and finance work, manually verify:

- Two simultaneous invoice requests cannot exceed the remaining order quantity.
- Two simultaneous credit notes cannot exceed the refundable quantity.
- Inventory and finance reversals occur exactly once.
- Historical records remain available after cancellation or archival.


Last Audit Snapshot

Audit date: July 30, 2026

- Vite production build: passed.
- Offline npm production dependency audit: passed with zero reported vulnerabilities.
- PHP/Pest suite: not executed during this audit because PHP and Composer were unavailable in the audit environment.
- Current test inventory: 41 Pest definitions in 10 files.
- Source changes made during audit: none; this README checklist was added afterward for collaborator handoff.


//Installation

composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run dev


//Running the Application

php artisan serve
npm run dev


//Development Notes

The application uses Laravel MVC architecture.
UI components are organized using Blade layouts and reusable components.
Sidebar navigation and module pages are structured to support future growth into a more complete ERP system.


//Roadmap

Planned improvements include:

Full CRUD for sales orders and customers
Database-backed reporting and forecasting data
Support ticket workflow enhancements
Notification system integration
Authentication and role-based access
Expanded testing and validation


//License

This project is currently maintained for internal development and extension purposes.
