<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trading_monthly_risk_stats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('risk_plan_id')
                ->constrained('trading_monthly_risk_plans')
                ->cascadeOnDelete();

            // Month (denormalized for fast queries)
            $table->year('risk_year');
            $table->tinyInteger('risk_month');

            // Trade impact summary
            $table->decimal('total_profit', 10, 2)->default(0);
            $table->decimal('total_loss', 10, 2)->default(0);

            // Dynamic risk calculation
            $table->decimal('current_allowed_loss', 10, 2)->default(0);
            $table->decimal('remaining_loss_balance', 10, 2)->default(0);

            // Trading control
            $table->boolean('trading_blocked')->default(false);
            $table->timestamp('blocked_at')->nullable();

            // Default columns
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'risk_year', 'risk_month'], 'uniq_user_month_risk_stats');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trading_monthly_risk_stats');
    }
};
