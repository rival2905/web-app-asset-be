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
        Schema::create('dinas_luars', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->references('id')->on('users');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('type_dl', [
                'Dinas Luar Full',
                'Dinas Luar - Masuk Kerja',
                'Masuk Kerja - Dinas Luar',
                'Dinas Luar Hari Libur',
                'Work From Home ( WFH )'
            ]);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('tujuan');
            $table->string('kegiatan');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->string('keterangan_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dinas_luars');
    }
};
