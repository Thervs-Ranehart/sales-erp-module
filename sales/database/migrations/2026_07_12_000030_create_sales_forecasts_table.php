<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales_forecasts', function (Blueprint $table) {
            $table->id('forecast_id');
            $table->date('forecast_period_start')->nullable();
            $table->date('forecast_period_end')->nullable();
            $table->string('forecast_method')->nullable();
            $table->integer('predicted_orders')->default(0);
            $table->decimal('predicted_revenue', 15, 2)->default(0);
            $table->decimal('predicted_growth', 8, 2)->default(0);
            $table->decimal('confidence_level', 8, 2)->default(0);
            $table->foreignId('generated_by')->constrained('employees')->cascadeOnDelete();
            $table->timestamp('generated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_forecasts');
    }
};