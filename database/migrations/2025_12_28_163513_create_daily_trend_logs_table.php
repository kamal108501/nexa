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
            $table->unsignedBigInteger('symbol_id')->index('daily_trend_logs_symbol_id_foreign');
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_trend_logs');
    }
};
