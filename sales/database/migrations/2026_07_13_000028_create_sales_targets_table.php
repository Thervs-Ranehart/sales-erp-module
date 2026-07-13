<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->increments('target_id');
            $table->unsignedInteger('employee_id');
            $table->integer('target_month')->nullable();
            $table->integer('target_year')->nullable();
            $table->decimal('sales_target', 15, 2)->nullable();
            $table->decimal('revenue_target', 15, 2)->nullable();
            $table->unsignedInteger('created_by');
            $table->timestamp('created_at')->nullable();

            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('created_by')->references('employee_id')->on('employees');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_targets');
    }
};
