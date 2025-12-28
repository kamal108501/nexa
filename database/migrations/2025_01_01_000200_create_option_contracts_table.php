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
            $table->unsignedBigInteger('symbol_id');
            $table->date('expiry_date');
            $table->decimal('strike_price', 10);
            $table->enum('option_type', ['CE', 'PE']);
            $table->string('contract_code')->default('');
            $table->integer('lot_size');
            $table->decimal('tick_size', 6)->default(0.05);
            $table->boolean('is_weekly')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['symbol_id', 'expiry_date']);
            $table->unique(['symbol_id', 'expiry_date', 'strike_price', 'option_type'], 'unique_option_contract');
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
