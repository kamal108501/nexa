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
        Schema::create('daily_trend_analysis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('symbol_id')->constrained('symbols')->cascadeOnDelete();

            $table->date('analysis_date');

            $table->enum('trend_type', ['bullish', 'bearish', 'sideways']);
            $table->tinyInteger('trend_strength')->comment('1 to 5');

            $table->enum('timeframe', ['daily', 'weekly']);
            $table->date('based_on_date');

            $table->text('analysis_notes')->nullable();

            $table->boolean('is_trend_correct')->nullable();
            $table->timestamp('validated_at')->nullable();

            // Project standard columns
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Prevent duplicate analysis
            $table->unique(
                ['user_id', 'symbol_id', 'analysis_date'],
                'unique_daily_trend_analysis'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_trend_analysis');
    }
};
