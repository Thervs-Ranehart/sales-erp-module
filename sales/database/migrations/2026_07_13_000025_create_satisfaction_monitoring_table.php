<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satisfaction_monitoring', function (Blueprint $table) {
            $table->increments('feedback_id');
            $table->unsignedInteger('ticket_id');
            $table->integer('rating')->nullable();
            $table->string('satisfaction_level')->nullable();
            $table->text('comments')->nullable();
            $table->dateTime('submitted_at')->nullable();

            $table->foreign('ticket_id')->references('ticket_id')->on('support_tickets');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('satisfaction_monitoring');
    }
};
