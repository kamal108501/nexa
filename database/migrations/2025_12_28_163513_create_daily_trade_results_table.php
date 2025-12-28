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
        Schema::create('daily_trade_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('daily_trade_plan_id')->index();
            $table->date('trade_date')->index();
            $table->dateTime('entry_time')->nullable();
            $table->dateTime('exit_time')->nullable();
            $table->decimal('entry_price', 10);
            $table->decimal('exit_price', 10);
            $table->decimal('points', 10)->comment('exit_price - entry_price');
            $table->decimal('pnl_amount', 12)->comment('(exit_price - entry_price) * lots * lot_size');
            $table->decimal('pnl_percent', 6)->comment('(pnl_amount / invested_amount) * 100');
            $table->enum('result', ['profit', 'loss'])->index();
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
        Schema::dropIfExists('daily_trade_results');
    }
};
