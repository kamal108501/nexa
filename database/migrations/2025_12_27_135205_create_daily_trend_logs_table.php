<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('daily_trend_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('symbol_id')->constrained('trading_symbols');
            $table->date('trend_date');
            $table->enum('predicted_trend', ['bullish', 'bearish', 'sideways']);
            $table->enum('actual_trend', ['bullish', 'bearish', 'sideways'])->nullable();
            $table->boolean('is_prediction_correct')->nullable();
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_trend_logs');
    }
};
