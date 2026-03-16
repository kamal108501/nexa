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
        Schema::table('daily_trade_plans', function (Blueprint $table) {
            if (! Schema::hasColumn('daily_trade_plans', 'option_contract_id')) {
                $table->foreignId('option_contract_id')
                    ->nullable()
                    ->after('trading_symbol_id')
                    ->constrained('option_contracts')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_trade_plans', function (Blueprint $table) {
            if (Schema::hasColumn('daily_trade_plans', 'option_contract_id')) {
                $table->dropConstrainedForeignId('option_contract_id');
            }
        });
    }
};
