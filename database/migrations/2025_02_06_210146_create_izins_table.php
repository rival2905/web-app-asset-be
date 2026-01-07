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
        Schema::create('izins', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->references('id')->on('users');
            $table->date('tanggal');
            $table->enum('jenis_izin', [
                'Izin Sakit',
                'Izin Cuti',
                'Izin Khusus',
                'Izin Terlambat',
                'Izin Pulang Cepat',
                'Izin Lainnya'
            ]);
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak']);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izins');
    }
};
