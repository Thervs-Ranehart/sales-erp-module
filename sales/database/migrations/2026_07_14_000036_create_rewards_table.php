<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->increments('reward_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('points_required')->default(0);
            $table->string('icon')->default('bi-gift'); // Bootstrap Icons class
            $table->string('status')->default('available'); // available | limited | unavailable
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};