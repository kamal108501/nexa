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
        Schema::create('stock_daily_prices', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Instrument (equity only)
            $table->foreignId('trading_symbol_id')
                ->constrained('trading_symbols')
                ->cascadeOnDelete();

            // Trading day
            $table->date('price_date');

            // OHLC prices
            $table->decimal('open_price', 10, 2)->nullable();
            $table->decimal('high_price', 10, 2)->nullable();
            $table->decimal('low_price', 10, 2)->nullable();
            $table->decimal('close_price', 10, 2)->nullable();

            // Optional data
            $table->unsignedBigInteger('volume')->nullable();
            $table->string('source', 50)->default('YAHOO');

            $table->boolean('is_active')->default(true);

            // ===== Audit fields =====
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();

            // ===== Constraints & Indexes =====
            $table->unique(
                ['trading_symbol_id', 'price_date'],
                'uniq_stock_symbol_price_date'
            );

            $table->index(['price_date', 'is_active'], 'idx_stock_price_date');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_daily_prices');
    }
};
