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
        Schema::create('stock_tips', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Instrument (equity only)
            $table->foreignId('trading_symbol_id')
                ->constrained('trading_symbols')
                ->cascadeOnDelete();

            // Tip details
            $table->date('tip_date');

            $table->decimal('buy_price', 10, 2);
            $table->decimal('stop_loss', 10, 2);
            $table->decimal('target_price', 10, 2);

            // Holding / validity
            $table->unsignedInteger('holding_days')->nullable();
            $table->date('expiry_date')->nullable();

            $table->decimal('expected_return_percent', 6, 2)->nullable();

            // Status
            $table->enum('status', ['ACTIVE', 'COMPLETED', 'EXPIRED'])
                ->default('ACTIVE');

            $table->text('notes')->nullable();

            $table->boolean('is_active')->default(true);

            // ===== Audit fields =====
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();

            // ===== Indexes =====
            $table->index(['tip_date', 'status', 'is_active'], 'idx_stock_tip_date_status');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_tips');
    }
};
