<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_behavior_analysis', function (Blueprint $table) {
            $table->increments('analysis_id');
            $table->unsignedInteger('customer_id');
            $table->date('analysis_period_start')->nullable();
            $table->date('analysis_period_end')->nullable();
            $table->integer('total_orders')->nullable();
            $table->decimal('total_spent', 15, 2)->nullable();
            $table->decimal('average_order_value', 15, 2)->nullable();
            $table->string('favorite_product_category')->nullable();
            $table->decimal('customer_lifetime_value', 15, 2)->nullable();
            $table->timestamp('generated_at')->nullable();

            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_behavior_analysis');
    }
};
