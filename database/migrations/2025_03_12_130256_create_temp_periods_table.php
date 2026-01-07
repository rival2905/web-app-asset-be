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
        Schema::create('temp_periods', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->json('first_periode')->nullable();
            $table->json('second_periode')->nullable();
            $table->json('full_periode')->nullable();
            $table->date('start_first_periode')->nullable();
            $table->date('end_first_periode')->nullable();
            $table->date('start_second_periode')->nullable();
            $table->date('end_second_periode')->nullable();
            $table->date('start_full_periode')->nullable();
            $table->date('end_full_periode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_periods');
    }
};
