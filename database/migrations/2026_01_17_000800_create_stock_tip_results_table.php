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
        Schema::create('stock_tip_results', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Related tip
            $table->foreignId('stock_tip_id')
                ->constrained('stock_tips')
                ->cascadeOnDelete();

            // Exit info
            $table->decimal('exit_price', 10, 2);
            $table->date('exit_date');

            $table->decimal('pnl_amount', 12, 2);
            $table->decimal('pnl_percent', 6, 2);

            $table->enum('exit_reason', [
                'TARGET_HIT',
                'SL_HIT',
                'TIME_EXPIRED'
            ]);

            $table->boolean('is_correct');

            $table->boolean('is_active')->default(true);

            // ===== Audit fields =====
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();

            // ===== Indexes =====
            $table->index(['exit_date', 'is_active'], 'idx_exit_date_active');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_tip_results');
    }
};
