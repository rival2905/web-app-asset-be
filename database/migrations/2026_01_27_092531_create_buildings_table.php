<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();

<<<<<<< HEAD
            $table->foreignId('unit_id')
                  ->nullable()
                  ->constrained('master_units') // ⬅️ FIX DI SINI
                  ->nullOnDelete();
=======
   $table->unsignedBigInteger('unit_id')->nullable();
   $table->foreign('unit_id')
      ->references('id')
      ->on('units')
      ->nullOnDelete();

    $table->timestamps();
});
>>>>>>> 374e677d9ea867a26107a4c86c97a184d9578d9d

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
