<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMaterial extends Model
{
    use HasFactory;

    protected $table = 'asset_materials';

    protected $fillable = [
        'name',
        'slug',
        'asset_category_id', // ✅ GANTI DARI asset_type_id
        'brand_id',
        'seri_id',
    ];
}
