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
        Schema::create('daily_trade_results', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Relation to plan
            $table->foreignId('daily_trade_plan_id')
                ->constrained('daily_trade_plans')
                ->cascadeOnDelete();

            // Trade timing
            $table->date('trade_date');
            $table->dateTime('entry_time')->nullable();
            $table->dateTime('exit_time')->nullable();

            // Prices & P/L
            $table->decimal('entry_price', 10, 2);
            $table->decimal('exit_price', 10, 2);

            $table->decimal('points', 10, 2)
                ->comment('exit_price - entry_price');

            $table->decimal('pnl_amount', 12, 2)
                ->comment('(exit_price - entry_price) * quantity * lot_size');

            $table->decimal('pnl_percent', 6, 2)
                ->comment('(pnl_amount / invested_amount) * 100');

            // Result
            $table->enum('result', ['PROFIT', 'LOSS']);

            $table->boolean('is_active')->default(true);

            // ===== Audit fields =====
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();

            // ===== Indexes =====
            $table->index(['trade_date', 'is_active'], 'idx_trade_date_active');
            $table->index('result');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_trade_results');
    }
};
