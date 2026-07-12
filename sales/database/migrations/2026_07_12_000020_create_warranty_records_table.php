<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('warranty_records', function (Blueprint $table) {
            $table->id('warranty_id');
            $table->foreignId('order_id')->constrained('sales_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('warranty_number')->unique();
            $table->date('warranty_start')->nullable();
            $table->date('warranty_end')->nullable();
            $table->string('warranty_status')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_records');
    }
};