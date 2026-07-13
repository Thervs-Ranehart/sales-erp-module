<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            Schema::create('customer_profiles_repaired', function (Blueprint $table) {
                $table->increments('profile_id');
                $table->unsignedInteger('customer_id')->unique();
                $table->string('gender')->nullable();
                $table->date('birth_date')->nullable();
                $table->string('preferred_contact')->nullable();
                $table->string('preferred_product_category')->nullable();
                $table->boolean('marketing_consent')->default(false);
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();

                $table->foreign('customer_id')
                    ->references('customer_id')
                    ->on('customers');
            });

            DB::statement(
                'INSERT INTO customer_profiles_repaired (
                    profile_id,
                    customer_id,
                    gender,
                    birth_date,
                    preferred_contact,
                    preferred_product_category,
                    marketing_consent,
                    created_at,
                    updated_at
                )
                SELECT
                    profile_id,
                    customer_id,
                    gender,
                    birth_date,
                    preferred_contact,
                    preferred_product_category,
                    marketing_consent,
                    created_at,
                    updated_at
                FROM customer_profiles'
            );

            Schema::dropIfExists('customer_profiles');
            Schema::rename('customer_profiles_repaired', 'customer_profiles');
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        // This repair should not be reversed.
    }
};