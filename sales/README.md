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

The project is currently in an early-to-mid development stage. The UI has been structured into reusable layout and component patterns, and core ERP modules have been scaffolded. Some areas are still being expanded with real data integration and deeper business logic.


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