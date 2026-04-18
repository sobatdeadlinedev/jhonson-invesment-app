<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('level')->unsigned(); // 1, 2, 3, dst
            $table->text('note')->nullable();         // catatan admin (opsional)
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique('user_id'); // 1 user hanya punya 1 level manual
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_levels');
    }
};