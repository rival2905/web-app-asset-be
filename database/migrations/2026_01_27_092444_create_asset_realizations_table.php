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
        Schema::create('asset_realizations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asset_id')->unsigned()->nullable();
            $table->date('date')->nullable();
            $table->bigInteger('room')->unsigned()->nullable();
            $table->bigInteger('detail_asset_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_realizations');
    }
};
