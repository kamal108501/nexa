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
        Schema::create('trading_symbols', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('symbol_code')->unique();
            $table->string('name');
            $table->string('exchange')->nullable();
            $table->string('segment', 50);
            $table->integer('lot_size')->nullable();
            $table->decimal('tick_size')->default(0.05);
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
        Schema::dropIfExists('trading_symbols');
    }
};
