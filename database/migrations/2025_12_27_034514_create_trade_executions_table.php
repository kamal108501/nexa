<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_executions', function (Blueprint $table) {
            $table->id();

            // --- Link to planned trade ---
            $table->foreignId('daily_trade_plan_id')
                ->constrained('daily_trade_plans')
                ->cascadeOnDelete();

            /**
             * Execution status
             * pending   = order planned but not triggered
             * executed  = entry taken
             * exited    = exit done
             * cancelled = not traded
             */
            $table->enum('execution_status', [
                'pending',
                'executed',
                'exited',
                'cancelled'
            ])->default('pending');

            // --- Actual execution prices ---
            $table->decimal('entry_price', 12, 2)->nullable();
            $table->timestamp('entry_time')->nullable();

            $table->decimal('exit_price', 12, 2)->nullable();
            $table->timestamp('exit_time')->nullable();

            /**
             * Exit reason
             * target_hit, stop_loss_hit, manual_exit, time_exit
             */
            $table->enum('exit_reason', [
                'target_hit',
                'stop_loss_hit',
                'manual_exit',
                'time_exit'
            ])->nullable();

            // --- Quantity actually traded ---
            $table->integer('executed_quantity')->nullable();

            // --- Project standard columns ---
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            /**
             * HARD RULE:
             * One execution per trade plan
             */
            $table->unique(
                ['daily_trade_plan_id'],
                'unique_execution_per_trade_plan'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_executions');
    }
};
