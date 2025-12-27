<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_tip_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_tip_id')->constrained('stock_tips');

            $table->decimal('exit_price', 10, 2);
            $table->date('exit_date');

            $table->decimal('pnl_amount', 12, 2);
            $table->decimal('pnl_percent', 6, 2);

            $table->enum('exit_reason', ['target_hit', 'sl_hit', 'time_expired']);
            $table->boolean('is_correct');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_tip_results');
    }
};
