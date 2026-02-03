<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetDetail extends Model
{
    protected $table = 'asset_details';

    protected $fillable = [
        'asset_id',
        'name',
        'slug'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
