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
            $table->date('trade_date')->unique();
            $table->enum('segment', ['STOCK', 'OPTION', 'COMMODITY'])->default('OPTION');
            $table->unsignedInteger('total_trades')->default(0);
            $table->unsignedInteger('winning_trades')->default(0);
            $table->unsignedInteger('losing_trades')->default(0);
            $table->decimal('gross_profit', 12)->default(0);
            $table->decimal('gross_loss', 12)->default(0);
            $table->decimal('net_pl', 12)->default(0);
            $table->decimal('capital_used', 14)->default(0);
            $table->decimal('roi_percent', 6)->default(0);
            $table->unsignedTinyInteger('discipline_score')->nullable();
            $table->text('remark')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['trade_date', 'segment']);
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
