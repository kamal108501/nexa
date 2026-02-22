<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('daily_mala_counts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->datetime('start');
            $table->datetime('end');
            $table->boolean('allDay')->default(true);
            $table->integer('mala_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_mala_counts');
    }
};
