<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_behavior_analysis', function (Blueprint $table) {
            $table->id('analysis_id');
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->date('analysis_period_start')->nullable();
            $table->date('analysis_period_end')->nullable();
            $table->integer('total_orders')->default(0);
            $table->decimal('total_spent', 15, 2)->default(0);
            $table->decimal('average_order_value', 15, 2)->default(0);
            $table->string('favorite_product_category')->nullable();
            $table->decimal('customer_lifetime_value', 15, 2)->default(0);
            $table->timestamp('generated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_behavior_analysis');
    }
};