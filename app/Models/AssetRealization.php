<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRealization extends Model
{
    use HasFactory;

    protected $table = 'asset_realizations';

    protected $fillable = [
        'name',
        'slug',
        'building_id',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
