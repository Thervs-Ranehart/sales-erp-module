<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('notification_type')->nullable();
            $table->string('title')->nullable();
            $table->text('message')->nullable();
            $table->string('related_module')->nullable();
            $table->unsignedBigInteger('related_record_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};