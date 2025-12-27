<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('daily_trade_plans', function (Blueprint $table) {
            $table->id();
            $table->date('trade_date');
            $table->foreignId('symbol_id')->constrained('trading_symbols');
            $table->foreignId('option_contract_id')->constrained('option_contracts');

            $table->decimal('current_price', 10, 2);
            $table->decimal('planned_entry_price', 10, 2);
            $table->decimal('stop_loss', 10, 2);
            $table->decimal('target_price', 10, 2);

            $table->integer('quantity')->default(1);

            $table->decimal('expected_profit', 12, 2)->nullable();
            $table->decimal('expected_loss', 12, 2)->nullable();
            $table->decimal('expected_return_percent', 6, 2)->nullable();

            $table->enum('status', ['planned', 'executed', 'skipped'])->default('planned');
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
        Schema::dropIfExists('daily_trade_plans');
    }
};
