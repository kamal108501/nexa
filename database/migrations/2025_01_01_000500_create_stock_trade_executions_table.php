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
        Schema::create('stock_trade_executions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('symbol_id')->index('stock_trade_executions_symbol_id_foreign');
            $table->unsignedBigInteger('stock_tip_id')->nullable()->index('stock_trade_executions_stock_tip_id_foreign');
            $table->enum('execution_type', ['buy', 'sell']);
            $table->integer('quantity');
            $table->decimal('price', 12);
            $table->date('execution_date');
            $table->text('execution_notes')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('stock_trade_executions');
    }
};
