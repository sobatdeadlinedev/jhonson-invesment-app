<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trading_signals', function (Blueprint $table) {
            // Bet configuration
            $table->enum('bet_type', ['percentage', 'fixed'])
                ->default('percentage')
                ->after('coin');

            $table->decimal('bet_value', 15, 2)
                ->default(1.00)
                ->after('bet_type');

            // User access control
            $table->boolean('is_public')
                ->default(true)
                ->after('bet_value');

            // Add indexes
            $table->index('bet_type');
            $table->index('is_public');
        });
    }

    public function down(): void
    {
        Schema::table('trading_signals', function (Blueprint $table) {
            $table->dropIndex(['bet_type']);
            $table->dropIndex(['is_public']);

            $table->dropColumn(['bet_type', 'bet_value', 'is_public']);
        });
    }
};
