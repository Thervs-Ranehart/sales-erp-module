<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales_performance', function (Blueprint $table) {
            $table->id('performance_id');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->integer('evaluation_month')->nullable();
            $table->integer('evaluation_year')->nullable();
            $table->integer('actual_orders')->default(0);
            $table->decimal('actual_revenue', 15, 2)->default(0);
            $table->decimal('target_achievement', 8, 2)->default(0);
            $table->string('performance_status')->nullable();
            $table->timestamp('evaluated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_performance');
    }
};