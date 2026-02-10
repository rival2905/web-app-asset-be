<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRealization extends Model
{
    use HasFactory;

    protected $table = 'asset_realizations';

    protected $fillable = [
        'asset_id',
        'date',
        'room',          // ← Ganti jadi room (text)
        'detail_asset'
    ];

    // Relasi ke Asset
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}