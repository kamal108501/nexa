<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trading_symbols', function (Blueprint $table) {
            $table->id();
            $table->string('symbol_code')->unique(); // NIFTY, RELIANCE
            $table->string('name');
            $table->enum('segment', ['INDEX', 'STOCK']);
            $table->integer('lot_size')->nullable();
            $table->decimal('tick_size', 8, 2)->default(0.05);
            $table->boolean('is_active')->default(true);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trading_symbols');
    }
};
