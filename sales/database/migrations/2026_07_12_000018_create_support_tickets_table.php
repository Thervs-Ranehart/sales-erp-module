<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id('ticket_id');
            $table->foreignId('order_id')->constrained('sales_orders')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('ticket_type')->nullable();
            $table->string('subject')->nullable();
            $table->text('description')->nullable();
            $table->string('priority')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->dateTime('closed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};