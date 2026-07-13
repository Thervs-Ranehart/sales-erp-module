<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->increments('inventory_transaction_id');
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('product_id');
            $table->integer('quantity_out')->nullable();
            $table->date('transaction_date')->nullable();

            $table->foreign('invoice_id')->references('invoice_id')->on('invoices');
            $table->foreign('product_id')->references('product_id')->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
