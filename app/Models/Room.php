<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $fillable = ['name', 'slug', 'building_id'];

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    // 🔥 TAMBAHAN - pilih salah satu sesuai struktur DB
    
    // OPSI 1: One-to-Many (jika asset punya room_id)
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    // ATAU

    // OPSI 2: Many-to-Many (jika ada tabel pivot asset_room)
    // public function assets()
    // {
    //     return $this->belongsToMany(Asset::class, 'asset_room');
    // }

    // Relasi ke AssetRealization
    public function assetRealizations()
    {
        return $this->hasMany(AssetRealization::class);
    }
}