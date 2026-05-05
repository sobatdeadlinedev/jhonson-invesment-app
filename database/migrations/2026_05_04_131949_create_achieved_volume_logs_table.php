<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achieved_volume_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 20, 2)->default(0); // selisih achieved_volume yang bertambah
            $table->decimal('achieved_before', 20, 2)->default(0); // nilai sebelum
            $table->decimal('achieved_after', 20, 2)->default(0);  // nilai sesudah
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achieved_volume_logs');
    }
};