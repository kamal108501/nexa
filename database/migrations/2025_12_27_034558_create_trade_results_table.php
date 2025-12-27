<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_results', function (Blueprint $table) {
            $table->id();

            // --- Links ---
            $table->foreignId('daily_trade_plan_id')
                ->constrained('daily_trade_plans')
                ->cascadeOnDelete();

            $table->foreignId('trade_execution_id')
                ->constrained('trade_executions')
                ->cascadeOnDelete();

            // --- Capital & prices ---
            $table->decimal('entry_price', 12, 2);
            $table->decimal('exit_price', 12, 2);
            $table->integer('quantity');

            // --- Investment & outcome ---
            $table->decimal('invested_amount', 14, 2);
            $table->decimal('gross_pnl', 14, 2)
                ->comment('Positive = profit, Negative = loss');

            $table->decimal('pnl_percentage', 8, 2);
            $table->decimal('r_multiple', 6, 2)
                ->comment('Risk-Reward multiple');

            // --- Result classification ---
            $table->boolean('is_profitable')
                ->comment('Used to allow 2nd trade');
            $table->enum('result_type', ['win', 'loss', 'breakeven']);

            // --- Charges (optional but future-proof) ---
            $table->decimal('brokerage', 10, 2)->default(0);
            $table->decimal('taxes', 10, 2)->default(0);
            $table->decimal('net_pnl', 14, 2)
                ->comment('gross_pnl - charges');

            // --- Project standard columns ---
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            /**
             * HARD RULES
             */
            $table->unique(
                ['daily_trade_plan_id'],
                'unique_result_per_trade_plan'
            );

            $table->unique(
                ['trade_execution_id'],
                'unique_result_per_execution'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_results');
    }
};
