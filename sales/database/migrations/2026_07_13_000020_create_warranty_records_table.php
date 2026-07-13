<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranty_records', function (Blueprint $table) {
            $table->increments('warranty_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_id');
            $table->string('warranty_number')->nullable()->unique();
            $table->date('warranty_start')->nullable();
            $table->date('warranty_end')->nullable();
            $table->string('warranty_status')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('order_id')->references('order_id')->on('sales_orders');
            $table->foreign('product_id')->references('product_id')->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_records');
    }
};
