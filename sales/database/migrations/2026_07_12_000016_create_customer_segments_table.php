<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_segments', function (Blueprint $table) {
            $table->id('segment_id');
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('segment_name')->nullable();
            $table->string('spending_category')->nullable();
            $table->string('purchase_frequency')->nullable();
            $table->timestamp('last_updated')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_segments');
    }
};