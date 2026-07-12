<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id('target_id');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->integer('target_month')->nullable();
            $table->integer('target_year')->nullable();
            $table->decimal('sales_target', 15, 2)->default(0);
            $table->decimal('revenue_target', 15, 2)->default(0);
            $table->foreignId('created_by')->constrained('employees')->cascadeOnDelete();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_targets');
    }
};