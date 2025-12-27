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
        Schema::create('symbols', function (Blueprint $table) {
            $table->id();

            $table->string('symbol_code', 50)->unique();
            $table->string('symbol_name', 150);

            $table->enum('instrument_category', ['equity', 'index', 'commodity']);
            $table->enum('instrument_type', ['stock', 'future', 'option']);

            $table->enum('exchange', ['NSE', 'MCX']);

            $table->integer('lot_size')->nullable();
            $table->decimal('tick_size', 6, 2)->nullable();

            $table->boolean('is_tradable')->default(true);
            $table->boolean('is_active')->default(true);

            // Project standard columns
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('symbols');
    }
};
