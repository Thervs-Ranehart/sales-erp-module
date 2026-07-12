<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loyalty_programs', function (Blueprint $table) {
            $table->id('loyalty_id');
            $table->foreignId('customer_id')->unique()->constrained('customers')->cascadeOnDelete();
            $table->string('membership_level')->nullable();
            $table->integer('points_earned')->default(0);
            $table->integer('points_redeemed')->default(0);
            $table->integer('available_points')->default(0);
            $table->date('enrollment_date')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_programs');
    }
};