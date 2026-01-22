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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('nik')->nullable();
            $table->string('nip')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('bidang')->nullable();
            $table->enum('role', ['admin', 'pegawai', 'mandor', 'pengamat', 'ksppj', 'subkoor', 'kuptd', 'admin-pusat'])->default('pegawai');
            $table->string('sub_kegiatan')->nullable();
            $table->integer('radius')->default('50');
            $table->string('fcm_token')->nullable();
            $table->string('password');
            $table->bigInteger('lokasi_kerja_id')->nullable();
            $table->string('avatar')->nullable();
            $table->string('identity_photo')->nullable();
            $table->bigInteger('mandor_id')->nullable()->references('id')->on('users');
            $table->bigInteger('pengamat_id')->nullable()->references('id')->on('users');
            $table->bigInteger('ksppj_id')->nullable()->references('id')->on('users');
            $table->bigInteger('subkoor')->nullable()->references('id')->on('users');
            $table->bigInteger('uptd_id')->nullable();
            $table->bigInteger('unit_id')->nullable();
            $table->timestamp('account_verified_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
