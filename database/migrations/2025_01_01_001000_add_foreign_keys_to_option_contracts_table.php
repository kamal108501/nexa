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
        Schema::table('option_contracts', function (Blueprint $table) {
            $table->foreign(['symbol_id'])->references(['id'])->on('trading_symbols')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('option_contracts', function (Blueprint $table) {
            $table->dropForeign('option_contracts_symbol_id_foreign');
        });
    }
};
