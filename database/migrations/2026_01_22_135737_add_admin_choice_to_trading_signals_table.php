<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trading_signals', function (Blueprint $table) {
            $table->enum('admin_choice', ['call', 'put'])->nullable()->after('result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trading_signals', function (Blueprint $table) {
            $table->dropColumn('admin_choice');
        });
    }
};
