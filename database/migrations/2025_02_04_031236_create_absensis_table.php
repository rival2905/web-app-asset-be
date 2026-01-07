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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->references('id')->on('users');
            $table->date('tanggal');
            $table->string('lokasi_masuk')->nullable();
            $table->string('latitude_masuk')->nullable();
            $table->string('longitude_masuk')->nullable();
            $table->string('foto_masuk')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->string('lokasi_keluar')->nullable();
            $table->string('latitude_keluar')->nullable();
            $table->string('longitude_keluar')->nullable();
            $table->string('foto_keluar')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->string('keterangan')->nullable();
            $table->bigInteger('dinas_luar_id')->references('id')->on('dinas_luars')->nullable();
            $table->bigInteger('izin_id')->references('id')->on('izins')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
