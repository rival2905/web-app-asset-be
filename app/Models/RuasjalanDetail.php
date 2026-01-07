<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuasjalanDetail extends Model
{
    use HasFactory;

    protected $table = 'master_ruas_jalan_detail';
    protected $connection = 'temanjabar';
}
