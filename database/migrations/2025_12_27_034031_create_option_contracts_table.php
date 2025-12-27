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
            $table->id();

            $table->foreignId('symbol_id')
                ->constrained('symbols')
                ->cascadeOnDelete();

            $table->date('expiry_date');
            $table->decimal('strike_price', 12, 2);
            $table->enum('option_type', ['CE', 'PE']);

            $table->integer('lot_size');
            $table->decimal('tick_size', 6, 2)->nullable();
            $table->string('contract_code', 100)->nullable();

            $table->boolean('is_weekly')->default(false);
            $table->boolean('is_active')->default(true);

            // Project standard audit columns
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Prevent duplicate option contracts
            $table->unique(
                ['symbol_id', 'expiry_date', 'strike_price', 'option_type'],
                'unique_option_contract'
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
