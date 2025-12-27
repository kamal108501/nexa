<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('option_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('symbol_id')->constrained('trading_symbols');
            $table->date('expiry_date');
            $table->decimal('strike_price', 10, 2);
            $table->enum('option_type', ['CE', 'PE']);
            $table->integer('lot_size');
            $table->boolean('is_weekly')->default(true);
            $table->boolean('is_active')->default(true);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['symbol_id', 'expiry_date', 'strike_price', 'option_type'],
                'unique_option_contract'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('option_contracts');
    }
};
