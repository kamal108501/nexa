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
        Schema::create('stock_trade_executions', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Instrument (equity)
            $table->foreignId('trading_symbol_id')
                ->constrained('trading_symbols')
                ->cascadeOnDelete();

            // Related tip (optional)
            $table->foreignId('stock_tip_id')
                ->nullable()
                ->constrained('stock_tips')
                ->nullOnDelete();

            // Execution details
            $table->enum('execution_type', ['BUY', 'SELL']);
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->dateTime('execution_at');

            $table->text('execution_notes')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            // ===== Audit fields =====
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();

            // ===== Indexes =====
            $table->index(['trading_symbol_id', 'execution_at', 'is_active'], 'idx_stock_exec_symbol_time');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_trade_executions');
    }
};
