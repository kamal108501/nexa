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
            $table->unsignedBigInteger('stock_tip_id')->index('stock_tip_results_stock_tip_id_foreign');
            $table->decimal('exit_price', 10);
            $table->date('exit_date');
            $table->decimal('pnl_amount', 12);
            $table->decimal('pnl_percent', 6);
            $table->enum('exit_reason', ['target_hit', 'sl_hit', 'time_expired']);
            $table->boolean('is_correct');
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
        Schema::dropIfExists('stock_tip_results');
    }
};
