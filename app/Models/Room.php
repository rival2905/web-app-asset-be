<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms'; // ubah dari asset_rooms menjadi rooms
    protected $fillable = ['name', 'slug', 'building_id'];

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }
}
