<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->increments('order_id');
            $table->string('order_number')->nullable()->unique();
            $table->unsignedInteger('quotation_id')->nullable();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('pricing_rule_id')->nullable();
            $table->date('order_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('order_status')->nullable();
            $table->string('warehouse')->nullable();
            $table->decimal('subtotal', 15, 2)->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->decimal('tax', 15, 2)->nullable();
            $table->decimal('shipping_fee', 15, 2)->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('quotation_id')->references('quotation_id')->on('quotations');
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('pricing_rule_id')->references('pricing_rule_id')->on('pricing_rules');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
