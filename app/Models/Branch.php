<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name', 'region_id', 'description', 'cover', 'latitude', 'longitude', 'open_time', 'close_time'
    ];

    protected $appends = ['cover_url'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(BranchAddress::class);
    }

    /**
     * Display cover image from url
     *
     * @return string
     */
    public function getCoverUrlAttribute()
    {
        return url('/api/images/branches/' . $this->id);
    }

}
