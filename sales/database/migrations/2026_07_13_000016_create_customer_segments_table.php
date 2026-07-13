<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_segments', function (Blueprint $table) {
            $table->increments('segment_id');
            $table->unsignedInteger('customer_id');
            $table->string('segment_name')->nullable();
            $table->string('spending_category')->nullable();
            $table->string('purchase_frequency')->nullable();
            $table->timestamp('last_updated')->nullable();

            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_segments');
    }
};
