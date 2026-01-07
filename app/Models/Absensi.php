<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dinas_luar()
    {
        return $this->hasOne(DinasLuar::class, 'id', 'dinas_luar_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function izin()
    {
        return $this->hasOne(Izin::class, 'id', 'izin_id');
    }
}
