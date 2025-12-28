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
            $table->foreign(['option_contract_id'])->references(['id'])->on('option_contracts')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['symbol_id'])->references(['id'])->on('trading_symbols')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_trade_plans', function (Blueprint $table) {
            $table->dropForeign('daily_trade_plans_option_contract_id_foreign');
            $table->dropForeign('daily_trade_plans_symbol_id_foreign');
        });
    }
};
