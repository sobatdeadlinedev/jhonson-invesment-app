<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trading_signals', function (Blueprint $table) {
            if (!Schema::hasColumn('trading_signals', 'scheduled_at')) {
                $table->timestamp('scheduled_at')->nullable()->after('is_public');
            }
        });
    }

    public function down(): void
    {
        Schema::table('trading_signals', function (Blueprint $table) {
            if (Schema::hasColumn('trading_signals', 'scheduled_at')) {
                $table->dropColumn('scheduled_at');
            }
        });
    }
};
