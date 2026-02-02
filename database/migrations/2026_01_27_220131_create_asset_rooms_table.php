<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
<<<<<<< HEAD
            $table->bigInteger('building_id')->unsigned()->nullable();
=======

            $table->foreignId('building_id')->nullable()->constrained('buildings')->nullOnDelete();

>>>>>>> 374e677d9ea867a26107a4c86c97a184d9578d9d
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_rooms');
    }
};
