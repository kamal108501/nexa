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
        Schema::create('daily_trade_plans', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Trade date
            $table->date('trade_date');

            // Instrument (stock / option / commodity)
            $table->foreignId('trading_symbol_id')
                ->constrained('trading_symbols')
                ->cascadeOnDelete();

            // Prices
            $table->decimal('current_price', 10, 2);
            $table->decimal('planned_entry_price', 10, 2);
            $table->decimal('stop_loss', 10, 2);
            $table->decimal('target_price', 10, 2);

            // Quantity / lots
            $table->integer('quantity')->default(1);

            // Expected metrics (optional)
            $table->decimal('expected_profit', 12, 2)->nullable();
            $table->decimal('expected_loss', 12, 2)->nullable();
            $table->decimal('expected_return_percent', 6, 2)->nullable();

            // Status
            $table->enum('status', ['PLANNED', 'EXECUTED', 'SKIPPED'])->default('PLANNED');

            $table->text('notes')->nullable();

            $table->boolean('is_active')->default(true);

            // ===== Audit fields =====
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();

            // ===== Indexes =====
            $table->index(['trade_date', 'status', 'is_active'], 'idx_trade_date_status');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_trade_plans');
    }
};
