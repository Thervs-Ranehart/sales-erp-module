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
        Schema::create('reward_redemptions', function (Blueprint $table) {
            $table->increments('redemption_id');
            $table->string('redemption_number')->nullable()->unique();
            $table->unsignedInteger('loyalty_id');
            $table->unsignedInteger('reward_id');
            $table->unsignedInteger('processed_by')->nullable();
            $table->integer('points_used');
            $table->integer('quantity')->default(1);
            $table->string('status')->default('Fulfilled');
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('loyalty_id')->references('loyalty_id')->on('loyalty_programs');
            $table->foreign('reward_id')->references('reward_id')->on('rewards');
            $table->foreign('processed_by')->references('employee_id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_redemptions');
    }
};
