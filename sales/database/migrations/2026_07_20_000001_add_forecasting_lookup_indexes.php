<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_targets', function (Blueprint $table): void {
            $table->unique(['employee_id', 'target_year', 'target_month'], 'sales_targets_employee_period_unique');
            $table->index(['target_year', 'target_month']);
        });
        Schema::table('sales_performance', function (Blueprint $table): void {
            $table->unique(['employee_id', 'evaluation_year', 'evaluation_month'], 'sales_performance_employee_period_unique');
            $table->index(['evaluation_year', 'evaluation_month']);
        });
        Schema::table('sales_orders', function (Blueprint $table): void {
            $table->index(['order_date', 'order_status']);
            $table->index(['employee_id', 'order_date']);
            $table->index(['warehouse', 'order_date']);
        });
    }

    public function down(): void
    {
        Schema::table('sales_targets', function (Blueprint $table): void {
            $table->dropUnique('sales_targets_employee_period_unique');
            $table->dropIndex(['target_year', 'target_month']);
        });
        Schema::table('sales_performance', function (Blueprint $table): void {
            $table->dropUnique('sales_performance_employee_period_unique');
            $table->dropIndex(['evaluation_year', 'evaluation_month']);
        });
        Schema::table('sales_orders', function (Blueprint $table): void {
            $table->dropIndex(['order_date', 'order_status']);
            $table->dropIndex(['employee_id', 'order_date']);
            $table->dropIndex(['warehouse', 'order_date']);
        });
    }
};
