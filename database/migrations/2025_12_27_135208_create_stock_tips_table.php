<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_tips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('symbol_id')->constrained('trading_symbols');
            $table->date('tip_date');

            $table->decimal('buy_price', 10, 2);
            $table->decimal('stop_loss', 10, 2);
            $table->decimal('target_price', 10, 2);

            $table->integer('holding_days');
            $table->date('expiry_date');

            $table->decimal('expected_return_percent', 6, 2)->nullable();
            $table->enum('status', ['active', 'completed', 'expired'])->default('active');
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
        Schema::dropIfExists('stock_tips');
    }
};
