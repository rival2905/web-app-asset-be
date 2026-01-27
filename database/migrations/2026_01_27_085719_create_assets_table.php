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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('size')->nullable();      
            $table->string('item_code')->nullable();
            $table->string('stock')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('asset_type_id')->unsigned()->nullable();
            $table->bigInteger('asset_material_id')->unsigned()->nullable();
            $table->bigInteger('brand_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
