<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'size',
        'item_code',
        'stock',
        'description',
        'asset_material_id',
        'brand_id',
    ];

    public function assetMaterial()
    {
        return $this->belongsTo(AssetMaterial::class, 'asset_material_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // 🔥 TAMBAHAN INI SAJA
    public function details()
    {
        return $this->hasMany(AssetDetail::class);
    }
}
