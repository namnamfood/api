<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegionStreet extends Model
{
    protected $fillable = [
        'name', 'latitude', 'longitude',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
