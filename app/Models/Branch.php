<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name', 'region_id', 'description', 'cover', 'latitude', 'longitude', 'open_time', 'close_time'
    ];

}
