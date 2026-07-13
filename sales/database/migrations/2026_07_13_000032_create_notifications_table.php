<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('notification_id');
            $table->unsignedInteger('employee_id');
            $table->string('notification_type')->nullable();
            $table->string('title')->nullable();
            $table->text('message')->nullable();
            $table->string('related_module')->nullable();
            $table->integer('related_record_id')->nullable();
            $table->boolean('is_read')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('employee_id')->references('employee_id')->on('employees');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
