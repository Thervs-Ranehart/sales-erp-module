<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forecast_recommendations', function (Blueprint $table) {
            $table->increments('recommendation_id');
            $table->unsignedInteger('forecast_id');
            $table->string('recommendation_type')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('priority')->nullable();
            $table->string('implementation_status')->nullable();
            $table->unsignedInteger('created_by');
            $table->timestamp('created_at')->nullable();

            $table->foreign('forecast_id')->references('forecast_id')->on('sales_forecasts');
            $table->foreign('created_by')->references('employee_id')->on('employees');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecast_recommendations');
    }
};
