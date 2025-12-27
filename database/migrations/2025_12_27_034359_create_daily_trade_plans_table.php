<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_trade_plans', function (Blueprint $table) {
            $table->id();

            // --- Discipline & ownership ---
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('trade_date');

            /**
             * Trade sequence
             * 1 = first trade of the day
             * 2 = second trade (allowed only if first is profitable - app logic)
             */
            $table->tinyInteger('trade_sequence')
                ->comment('1 = first trade, 2 = second trade');

            // --- Link to trend analysis ---
            $table->foreignId('daily_trend_analysis_id')
                ->constrained('daily_trend_analysis')
                ->cascadeOnDelete();

            // --- Instrument selection ---
            $table->foreignId('symbol_id')->constrained('symbols');
            $table->foreignId('option_contract_id')
                ->nullable()
                ->constrained('option_contracts');

            $table->enum('instrument_type', ['stock', 'option', 'future']);

            // --- Trade intent ---
            $table->enum('trade_direction', ['buy', 'sell']);
            $table->enum('trade_duration', ['intraday', 'short_term', 'positional']);

            // --- Price planning ---
            $table->decimal('planned_entry_price', 12, 2);
            $table->decimal('planned_stop_loss', 12, 2);
            $table->decimal('planned_target_price', 12, 2);

            // --- Capital & risk ---
            $table->integer('planned_quantity');
            $table->decimal('planned_investment', 14, 2);
            $table->decimal('planned_risk_amount', 14, 2);
            $table->decimal('planned_reward_amount', 14, 2);
            $table->decimal('risk_reward_ratio', 6, 2);

            // --- Plan lifecycle ---
            $table->enum('plan_status', [
                'planned',
                'executed',
                'cancelled',
                'expired'
            ])->default('planned');

            $table->text('plan_notes')->nullable();

            // --- Project standard columns ---
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            /**
             * HARD DB RULE:
             * - Max 2 trades per user per day
             * - Prevents 3rd trade automatically
             */
            $table->unique(
                ['user_id', 'trade_date', 'trade_sequence'],
                'unique_trade_sequence_per_day'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_trade_plans');
    }
};
