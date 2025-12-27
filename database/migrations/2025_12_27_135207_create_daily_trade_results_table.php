<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('daily_trade_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_trade_plan_id')->constrained('daily_trade_plans');

            $table->decimal('exit_price', 10, 2);
            $table->dateTime('exit_time')->nullable();

            $table->decimal('pnl_amount', 12, 2);
            $table->decimal('pnl_percent', 6, 2);
            $table->enum('result', ['profit', 'loss']);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_trade_results');
    }
};
