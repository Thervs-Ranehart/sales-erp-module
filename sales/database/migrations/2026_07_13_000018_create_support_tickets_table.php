<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->increments('ticket_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('product_id');
            $table->string('ticket_type')->nullable();
            $table->string('subject')->nullable();
            $table->text('description')->nullable();
            $table->string('priority')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->dateTime('closed_at')->nullable();

            $table->foreign('order_id')->references('order_id')->on('sales_orders');
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('product_id')->references('product_id')->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
