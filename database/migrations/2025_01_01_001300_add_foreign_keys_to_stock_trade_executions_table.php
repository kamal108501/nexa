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
        Schema::table('stock_trade_executions', function (Blueprint $table) {
            $table->foreign(['stock_tip_id'])->references(['id'])->on('stock_tips')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['symbol_id'])->references(['id'])->on('trading_symbols')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_trade_executions', function (Blueprint $table) {
            $table->dropForeign('stock_trade_executions_stock_tip_id_foreign');
            $table->dropForeign('stock_trade_executions_symbol_id_foreign');
        });
    }
};
