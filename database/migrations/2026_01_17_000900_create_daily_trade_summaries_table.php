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
        Schema::create('daily_trade_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Summary scope
            $table->date('trade_date');
            $table->enum('segment', ['STOCK', 'OPTION', 'COMMODITY'])
                ->default('OPTION');

            // Trade counts
            $table->unsignedInteger('total_trades')->default(0);
            $table->unsignedInteger('winning_trades')->default(0);
            $table->unsignedInteger('losing_trades')->default(0);

            // P/L
            $table->decimal('gross_profit', 12, 2)->default(0);
            $table->decimal('gross_loss', 12, 2)->default(0);
            $table->decimal('net_pl', 12, 2)->default(0);

            // Capital & ROI
            $table->decimal('capital_used', 14, 2)->default(0);
            $table->decimal('roi_percent', 6, 2)->default(0);

            // Discipline & notes
            $table->unsignedTinyInteger('discipline_score')->nullable();
            $table->text('remark')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            // ===== Audit fields =====
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();

            // ===== Constraints =====
            // ===== Indexes =====
            $table->index(['trade_date', 'is_active'], 'idx_trade_date_active');
            $table->index('deleted_at');

            $table->unique(
                ['trade_date', 'segment'],
                'uniq_trade_date_segment'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_trade_summaries');
    }
};
