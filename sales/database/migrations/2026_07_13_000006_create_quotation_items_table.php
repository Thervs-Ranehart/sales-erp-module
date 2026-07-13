<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->increments('quotation_item_id');
            $table->unsignedInteger('quotation_id');
            $table->unsignedInteger('product_id');
            $table->integer('quantity')->nullable();
            $table->decimal('unit_price', 15, 2)->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->decimal('subtotal', 15, 2)->nullable();

            $table->foreign('quotation_id')->references('quotation_id')->on('quotations');
            $table->foreign('product_id')->references('product_id')->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
