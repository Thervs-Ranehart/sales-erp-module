<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('forecast_recommendations', function (Blueprint $table) {
            $table->id('recommendation_id');
            $table->foreignId('forecast_id')->constrained('sales_forecasts')->cascadeOnDelete();
            $table->string('recommendation_type')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('priority')->nullable();
            $table->string('implementation_status')->nullable();
            $table->foreignId('created_by')->constrained('employees')->cascadeOnDelete();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecast_recommendations');
    }
};