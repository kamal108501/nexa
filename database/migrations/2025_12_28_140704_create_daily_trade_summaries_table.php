<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_trade_summaries', function (Blueprint $table) {
            $table->id();

            // 🔹 Core identity
            $table->date('trade_date')->unique();

            // 🔹 Market segment (future-proof)
            $table->enum('segment', ['STOCK', 'OPTION', 'COMMODITY'])
                ->default('OPTION');

            // 🔹 Trade counts
            $table->unsignedInteger('total_trades')->default(0);
            $table->unsignedInteger('winning_trades')->default(0);
            $table->unsignedInteger('losing_trades')->default(0);

            // 🔹 P&L metrics
            $table->decimal('gross_profit', 12, 2)->default(0);
            $table->decimal('gross_loss', 12, 2)->default(0);
            $table->decimal('net_pl', 12, 2)->default(0);

            // 🔹 Capital & efficiency
            $table->decimal('capital_used', 14, 2)->default(0);
            $table->decimal('roi_percent', 6, 2)->default(0);

            // 🔹 Discipline / journal
            $table->unsignedTinyInteger('discipline_score')->nullable(); // 1–10
            $table->text('remark')->nullable();

            // 🔹 NEXA standard audit columns
            $table->boolean('is_active')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // 🔹 Helpful indexes
            $table->index(['trade_date', 'segment']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_trade_summaries');
    }
};
