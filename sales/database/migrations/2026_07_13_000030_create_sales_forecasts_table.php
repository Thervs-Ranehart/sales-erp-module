<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_forecasts', function (Blueprint $table) {
            $table->increments('forecast_id');
            $table->date('forecast_period_start')->nullable();
            $table->date('forecast_period_end')->nullable();
            $table->string('forecast_method')->nullable();
            $table->integer('predicted_orders')->nullable();
            $table->decimal('predicted_revenue', 15, 2)->nullable();
            $table->decimal('predicted_growth', 15, 2)->nullable();
            $table->decimal('confidence_level', 15, 2)->nullable();
            $table->unsignedInteger('generated_by');
            $table->timestamp('generated_at')->nullable();

            $table->foreign('generated_by')->references('employee_id')->on('employees');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_forecasts');
    }
};
