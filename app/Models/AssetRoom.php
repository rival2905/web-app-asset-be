<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetRoom extends Model
{
    protected $table = 'rooms';

    protected $fillable = ['name', 'slug'];
}
