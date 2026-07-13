<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_performance', function (Blueprint $table) {
            $table->increments('performance_id');
            $table->unsignedInteger('employee_id');
            $table->integer('evaluation_month')->nullable();
            $table->integer('evaluation_year')->nullable();
            $table->integer('actual_orders')->nullable();
            $table->decimal('actual_revenue', 15, 2)->nullable();
            $table->decimal('target_achievement', 15, 2)->nullable();
            $table->string('performance_status')->nullable();
            $table->timestamp('evaluated_at')->nullable();

            $table->foreign('employee_id')->references('employee_id')->on('employees');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_performance');
    }
};
