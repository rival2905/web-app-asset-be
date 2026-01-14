<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
        'jam_masuk',
        'jam_keluar',
        'avatar',
        'device_id',
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
        'master_unit_id',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
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
    public function kepengamatan()
    {
        return $this->hasMany(MasterLokasiKerja::class, 'kd_sppjj', 'id');
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
    public function data_anulir()
    {
        return $this->hasMany(AnulirAbsensi::class, 'user_id', 'id');
    }
}
