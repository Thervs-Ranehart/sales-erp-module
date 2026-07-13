<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_programs', function (Blueprint $table) {
            $table->increments('loyalty_id');
            $table->unsignedInteger('customer_id')->unique();
            $table->string('membership_level')->nullable();
            $table->integer('points_earned')->nullable();
            $table->integer('points_redeemed')->nullable();
            $table->integer('available_points')->nullable();
            $table->date('enrollment_date')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_programs');
    }
};
