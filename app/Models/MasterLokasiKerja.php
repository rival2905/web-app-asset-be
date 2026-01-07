<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterLokasiKerja extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'lokasi_kerja_user', 'master_lokasi_kerja_id', 'user_id');
    }
}
