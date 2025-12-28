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
            $table->unsignedBigInteger('symbol_id')->index('stock_tips_symbol_id_foreign');
            $table->date('tip_date');
            $table->decimal('buy_price', 10);
            $table->decimal('stop_loss', 10);
            $table->decimal('target_price', 10);
            $table->integer('holding_days');
            $table->date('expiry_date');
            $table->decimal('expected_return_percent', 6)->nullable();
            $table->enum('status', ['active', 'completed', 'expired'])->default('active');
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
        Schema::dropIfExists('stock_tips');
    }
};
