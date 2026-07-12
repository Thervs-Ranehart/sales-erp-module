<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales_reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->string('report_name');
            $table->string('report_type');
            $table->date('report_period_start')->nullable();
            $table->date('report_period_end')->nullable();
            $table->foreignId('generated_by')->constrained('employees')->cascadeOnDelete();
            $table->timestamp('generated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_reports');
    }
};