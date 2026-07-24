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
        Schema::create('marketing_campaigns', function (Blueprint $table) {
            $table->increments('campaign_id');
            $table->unsignedInteger('created_by');
            $table->string('campaign_name');
            $table->string('objective')->nullable();
            $table->string('channel');
            $table->string('target_segment')->nullable();
            $table->string('target_loyalty_tier')->nullable();
            $table->string('status')->default('Draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->text('message');
            $table->timestamps();
            $table->foreign('created_by')->references('employee_id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_campaigns');
    }
};
