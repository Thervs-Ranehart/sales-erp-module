<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('report_metrics', function (Blueprint $table) {
            $table->id('metric_id');
            $table->foreignId('report_id')->constrained('sales_reports')->cascadeOnDelete();
            $table->string('metric_name');
            $table->decimal('metric_value', 15, 2);
            $table->text('remarks')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_metrics');
    }
};