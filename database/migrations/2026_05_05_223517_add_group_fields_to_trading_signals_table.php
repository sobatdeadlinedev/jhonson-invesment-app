<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trading_signals', function (Blueprint $table) {
            // ID ketua grup tunggal (nullable karena mode public/specific tidak pakai ini)
            $table->unsignedBigInteger('group_leader_id')->nullable()->after('is_public');

            // Kedalaman downline yang dipakai (1/3/5/10), default semua level
            $table->unsignedTinyInteger('group_depth')->default(10)->after('group_leader_id');

            // Snapshot jumlah member saat signal dibuat (opsional, untuk reporting)
            $table->unsignedInteger('group_total_members')->default(0)->after('group_depth');

            $table->foreign('group_leader_id')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('trading_signals', function (Blueprint $table) {
            $table->dropForeign(['group_leader_id']);
            $table->dropColumn(['group_leader_id', 'group_depth', 'group_total_members']);
        });
    }
};