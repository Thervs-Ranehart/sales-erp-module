<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_metrics', function (Blueprint $table) {
            $table->increments('metric_id');
            $table->unsignedInteger('report_id');
            $table->string('metric_name');
            $table->decimal('metric_value', 15, 2);
            $table->text('remarks')->nullable();

            $table->foreign('report_id')->references('report_id')->on('sales_reports');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_metrics');
    }
};
