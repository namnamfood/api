<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'latitude', 'longitude',
    ];

    public function streets()
    {
        return $this->hasMany(RegionStreet::class);
    }
}
