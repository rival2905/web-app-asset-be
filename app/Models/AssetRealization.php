<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetRealization extends Model
{
    protected $guarded = [];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function detailAsset()
    {
        return $this->belongsTo(AssetDetail::class, 'detail_asset_id');
    }
}