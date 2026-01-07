<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruasjalan extends Model
{
    use HasFactory;
    protected $table = 'master_ruas_jalan';
    protected $connection = 'temanjabar';
}
