<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_trade_executions', function (Blueprint $table) {
            $table->id();

            // ✅ MUST exist BEFORE FK
            $table->unsignedBigInteger('symbol_id');

            $table->unsignedBigInteger('stock_tip_id')->nullable();

            $table->enum('execution_type', ['buy', 'sell']);

            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->date('execution_date');

            $table->text('execution_notes')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // ✅ Foreign keys AFTER column definition
            $table->foreign('symbol_id')
                ->references('id')
                ->on('trading_symbols')
                ->cascadeOnDelete();

            $table->foreign('stock_tip_id')
                ->references('id')
                ->on('stock_tips')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_trade_executions');
    }
};
