<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_reports', function (Blueprint $table) {
            $table->increments('report_id');
            $table->string('report_name');
            $table->string('report_type');
            $table->date('report_period_start')->nullable();
            $table->date('report_period_end')->nullable();
            $table->unsignedInteger('generated_by');
            $table->timestamp('generated_at')->nullable();

            $table->foreign('generated_by')->references('employee_id')->on('employees');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_reports');
    }
};
