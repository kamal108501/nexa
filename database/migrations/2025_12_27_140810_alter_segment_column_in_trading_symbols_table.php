<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Change ENUM to STRING
        Schema::table('trading_symbols', function (Blueprint $table) {
            $table->string('segment', 50)->change();
        });
    }

    public function down(): void
    {
        // Rollback to ENUM (original values only)
        DB::statement("
            ALTER TABLE trading_symbols 
            MODIFY segment ENUM('INDEX', 'STOCK') NOT NULL
        ");
    }
};
