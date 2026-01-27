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
        Schema::create('detail_assets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asset_id')->unsigned();
            $table->string('number_seri')->nullable();
            $table->year('production_year')->nullable();
            $table->string('unit_price')->nullable();
            $table->string('condition')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_assets');
    }
};
