<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDetail extends Model
{
    use HasFactory;

    protected $table = 'asset_details';

    protected $fillable = [
        'asset_id',
        'number_seri',
        'production_year',
        'unit_price',
        'condition',
    ];

    /**
     * Relasi ke Asset
     * 1 Asset punya banyak AssetDetail
     */
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
