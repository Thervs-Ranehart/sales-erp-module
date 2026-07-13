<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->increments('quotation_id');
            $table->string('quotation_number')->nullable()->unique();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('pricing_rule_id')->nullable();
            $table->date('quotation_date')->nullable();
            $table->date('valid_until')->nullable();
            $table->decimal('subtotal', 15, 2)->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->decimal('tax', 15, 2)->nullable();
            $table->decimal('shipping_fee', 15, 2)->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->string('quotation_status')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('customer_id')
      ->references('customer_id')
      ->on('customers')
      ->cascadeOnDelete();
            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('pricing_rule_id')->references('pricing_rule_id')->on('pricing_rules');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
