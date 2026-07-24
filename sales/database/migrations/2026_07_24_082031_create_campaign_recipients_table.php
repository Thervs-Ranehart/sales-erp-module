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
        Schema::create('campaign_recipients', function (Blueprint $table) {
            $table->increments('recipient_id');
            $table->unsignedInteger('campaign_id');
            $table->unsignedInteger('customer_id');
            $table->string('delivery_status')->default('Queued');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();
            $table->foreign('campaign_id')->references('campaign_id')->on('marketing_campaigns')->cascadeOnDelete();
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->unique(['campaign_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_recipients');
    }
};
