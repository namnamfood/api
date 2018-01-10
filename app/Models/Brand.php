<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['name', 'cover'];
    protected $appends = ['cover_url'];

    /**
     * Get full url of cover image
     *
     * @return string
     */
    public function getCoverUrlAttribute()
    {
        return url('/api/images/brands/' . $this->id);
    }


}
