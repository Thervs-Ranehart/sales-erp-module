<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id('profile_id');
            $table->foreignId('customer_id')->unique()->constrained('customers')->cascadeOnDelete();
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('preferred_contact')->nullable();
            $table->string('preferred_product_category')->nullable();
            $table->boolean('marketing_consent')->default(false);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_profiles');
    }
};