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
        Schema::create('trading_symbols', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Core identity
            $table->string('symbol_code', 50);
            $table->string('name', 150);
            $table->string('exchange', 20)->nullable();   // NSE, BSE, MCX
            $table->string('segment', 50);                // STOCK, OPTION, COMMODITY

            // Trading properties
            $table->integer('lot_size')->nullable();
            $table->decimal('tick_size', 8, 4)->default(0.05);
            $table->boolean('is_active')->default(true);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();

            /* ---------------- Indexes ---------------- */
            $table->index(['segment', 'is_active'], 'idx_segment_active');
            $table->index('deleted_at');

            // Unique instrument per market + segment
            $table->unique(['symbol_code', 'exchange', 'segment'], 'uq_symbol_exchange_segment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_symbols');
    }
};
