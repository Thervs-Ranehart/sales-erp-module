<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_contracts', function (Blueprint $table) {
            $table->increments('contract_id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('product_id');
            $table->string('contract_number')->nullable()->unique();
            $table->string('service_type')->nullable();
            $table->date('service_start')->nullable();
            $table->date('service_end')->nullable();
            $table->string('contract_status')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('product_id')->references('product_id')->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_contracts');
    }
};
