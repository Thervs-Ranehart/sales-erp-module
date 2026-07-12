<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id('inventory_transaction_id');
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('quantity_out')->default(0);
            $table->date('transaction_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};