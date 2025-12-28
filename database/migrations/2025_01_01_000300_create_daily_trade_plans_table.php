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
        Schema::create('daily_trade_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('trade_date');
            $table->unsignedBigInteger('symbol_id')->index('daily_trade_plans_symbol_id_foreign');
            $table->unsignedBigInteger('option_contract_id')->index('daily_trade_plans_option_contract_id_foreign');
            $table->decimal('current_price', 10);
            $table->decimal('planned_entry_price', 10);
            $table->decimal('stop_loss', 10);
            $table->decimal('target_price', 10);
            $table->integer('quantity')->default(1);
            $table->decimal('expected_profit', 12)->nullable();
            $table->decimal('expected_loss', 12)->nullable();
            $table->decimal('expected_return_percent', 6)->nullable();
            $table->enum('status', ['planned', 'executed', 'skipped'])->default('planned');
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
        Schema::dropIfExists('daily_trade_plans');
    }
};
