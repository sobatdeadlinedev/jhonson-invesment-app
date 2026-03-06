<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk MySQL, gunakan raw SQL
        DB::statement("ALTER TABLE `signal_participants` MODIFY `joined_at` TIMESTAMP NULL DEFAULT NULL");
    }

    public function down(): void
    {
        // Kembalikan ke kondisi sebelumnya (dengan ON UPDATE)
        DB::statement("ALTER TABLE `signal_participants` MODIFY `joined_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP");
    }
};