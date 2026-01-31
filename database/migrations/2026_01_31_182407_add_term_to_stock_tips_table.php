<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_tips', function (Blueprint $table) {
            $table->string('term')->default('short_term')->after('status')->comment('short_term or long_term');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_tips', function (Blueprint $table) {
            $table->dropColumn('term');
        });
    }
};
