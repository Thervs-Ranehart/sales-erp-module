<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipment_items', function (Blueprint $table) {
            $table->increments('shipment_item_id');
            $table->unsignedInteger('shipment_id');
            $table->unsignedInteger('order_item_id');
            $table->integer('quantity');

            $table->foreign('shipment_id')->references('shipment_id')->on('shipments')->cascadeOnDelete();
            $table->foreign('order_item_id')->references('order_item_id')->on('sales_order_items');
            $table->unique(['shipment_id', 'order_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_items');
    }
};
