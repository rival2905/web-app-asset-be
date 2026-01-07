<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTemp extends Model
{
    use HasFactory;

    protected $table = "users_temporary";
    protected $guarded = [];


    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $with = ['lokasi_kerja', 'absensi_today', 'dinas_luar_today', 'izin_today'];

    public function lokasi_kerja()
    {
        return $this->belongsToMany(MasterLokasiKerja::class, 'lokasi_kerjas', 'user_id', 'master_lokasi_kerja_id');
    }

    public function absensi_today()
    {
        //get last absensi
        return $this->hasOne(Absensi::class, 'user_id', 'id')->whereDate('tanggal', now())->latest();
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'user_id', 'id');
    }

    public function dinas_luar_today()
    {
        return $this->hasOne(DinasLuar::class, 'user_id', 'id')
            ->where('status', 'Disetujui')
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now());
    }

    public function dinas_luar()
    {
        return $this->hasMany(DinasLuar::class, 'user_id', 'id');
    }

    public function izin_today()
    {
        return $this->hasOne(Izin::class, 'user_id', 'id')->whereDate('created_at', now())->where('status', 'Disetujui');
    }

    public function izin()
    {
        return $this->hasMany(Izin::class, 'user_id', 'id');
    }

    public function mandor()
    {
        return $this->belongsTo(User::class, 'mandor_id');
    }
    public function pengamat()
    {
        return $this->belongsTo(User::class, 'pengamat_id');
    }
}
