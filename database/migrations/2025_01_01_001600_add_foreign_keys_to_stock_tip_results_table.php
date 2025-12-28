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
        Schema::table('stock_tip_results', function (Blueprint $table) {
            $table->foreign(['stock_tip_id'])->references(['id'])->on('stock_tips')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_tip_results', function (Blueprint $table) {
            $table->dropForeign('stock_tip_results_stock_tip_id_foreign');
        });
    }
};
