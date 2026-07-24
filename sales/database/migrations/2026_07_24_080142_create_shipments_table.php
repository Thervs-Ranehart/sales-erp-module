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
        Schema::create('shipments', function (Blueprint $table) {
            $table->increments('shipment_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('created_by');
            $table->string('shipment_number')->nullable()->unique();
            $table->string('carrier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('shipment_status')->default('Packed');
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->string('proof_of_delivery')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('sales_orders');
            $table->foreign('created_by')->references('employee_id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
