<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('signal_allowed_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('signal_id')
                ->constrained('trading_signals')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();

            // Unique constraint - satu user hanya bisa ada sekali per signal
            $table->unique(['signal_id', 'user_id']);

            // Indexes untuk query performance
            $table->index('signal_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signal_allowed_users');
    }
};
