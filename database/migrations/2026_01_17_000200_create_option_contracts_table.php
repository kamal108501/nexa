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
        Schema::create('option_contracts', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Instrument
            $table->foreignId('trading_symbol_id')
                ->constrained('trading_symbols')
                ->cascadeOnDelete();

            // Contract details
            $table->date('expiry_date');
            $table->decimal('strike_price', 10, 2);
            $table->enum('option_type', ['CE', 'PE']);
            $table->string('contract_code')->default('');

            // Trading properties
            $table->integer('lot_size');
            $table->decimal('tick_size', 8, 4)->default(0.05);
            $table->boolean('is_weekly')->default(true);
            $table->boolean('is_active')->default(true);

            // ===== Audit fields =====
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();

            // ===== Indexes =====
            $table->index(['expiry_date', 'is_active'], 'idx_expiry_active');
            $table->index('deleted_at');

            // ===== Constraints =====
            $table->unique(
                ['trading_symbol_id', 'expiry_date', 'strike_price', 'option_type'],
                'uniq_option_contract'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('option_contracts');
    }
};
