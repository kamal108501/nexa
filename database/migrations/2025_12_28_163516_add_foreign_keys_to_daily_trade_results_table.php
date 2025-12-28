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
        Schema::table('daily_trade_results', function (Blueprint $table) {
            $table->foreign(['daily_trade_plan_id'])->references(['id'])->on('daily_trade_plans')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_trade_results', function (Blueprint $table) {
            $table->dropForeign('daily_trade_results_daily_trade_plan_id_foreign');
        });
    }
};
