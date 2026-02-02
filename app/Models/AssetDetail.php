<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDetail extends Model
{
    protected $table = 'asset_details';

    protected $fillable = ['name', 'slug'];
}
