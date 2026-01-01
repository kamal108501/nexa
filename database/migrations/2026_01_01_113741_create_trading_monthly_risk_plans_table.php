<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trading_monthly_risk_plans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Month definition
            $table->year('risk_year');            // 2025
            $table->tinyInteger('risk_month');     // 1 = Jan, 2 = Feb

            // Risk rules
            $table->decimal('base_max_loss', 10, 2);
            $table->decimal('profit_risk_percent', 5, 2)->default(10.00);

            $table->boolean('carry_profit_to_next_month')->default(true);
            $table->boolean('is_locked')->default(false);

            // Default columns
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'risk_year', 'risk_month'], 'uniq_user_month_risk');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trading_monthly_risk_plans');
    }
};
