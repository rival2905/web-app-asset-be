<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Absensi;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nik',
        'jabatan',
        'bidang',
        'fcm_token',
        'role',
        'lokasi_kerja_id',
        'avatar',
        'mandor_id',
        'pengamat_id',
        'ksppj_id',
        'subkoor',
        'uptd_id',
        'nip',
        'sub_kegiatan',
        'radius',
        'enable_persensi',
        'no_hp',
        'identity_photo',
        'account_verified_at',
        'deleted_at',
        'deleted_by',
        'restored_by',
        'unit_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function lokasi_kerja()
    {
        return $this->belongsToMany(
            MasterLokasiKerja::class,
            'lokasi_kerjas',
            'user_id',
            'master_lokasi_kerja_id'
        );
    }

    public function kepengamatan()
    {
        return $this->hasMany(MasterLokasiKerja::class, 'kd_sppjj', 'id');
    }

    public function mandor()
    {
        return $this->belongsTo(User::class, 'mandor_id');
    }

    public function pengamat()
    {
        return $this->belongsTo(User::class, 'pengamat_id');
    }

    // ✅ TAMBAHAN SAJA (tidak menghapus apa pun)
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'user_id');
    }
}
