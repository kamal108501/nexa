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
        Schema::create('daily_trend_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Instrument
            $table->foreignId('trading_symbol_id')
                ->constrained('trading_symbols')
                ->cascadeOnDelete();

            // Trend info
            $table->date('trend_date');

            $table->enum('predicted_trend', ['BULLISH', 'BEARISH', 'SIDEWAYS']);
            $table->enum('actual_trend', ['BULLISH', 'BEARISH', 'SIDEWAYS'])->nullable();

            $table->boolean('is_prediction_correct')->nullable();
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
            $table->index(
                ['trading_symbol_id', 'trend_date', 'is_active', 'deleted_at'],
                'idx_trend_symbol_date'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_trend_logs');
    }
};
