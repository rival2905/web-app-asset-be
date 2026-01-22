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


    public function lokasi_kerja()
    {
        return $this->belongsToMany(MasterLokasiKerja::class, 'lokasi_kerjas', 'user_id', 'master_lokasi_kerja_id');
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
