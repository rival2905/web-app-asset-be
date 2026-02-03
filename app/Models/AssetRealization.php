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
    'room_id',
    'detail_asset',
];
}
    