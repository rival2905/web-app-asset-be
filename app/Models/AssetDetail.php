<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetDetail extends Model
{
    protected $table = 'asset_details';

    protected $fillable = [
        'asset_id',
        'number_seri',
        'production_year',
        'unit_price',
        'condition'
    ];

    // Relasi ke Asset
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
