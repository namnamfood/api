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


    /**
     * This method returns available products which belongs to brand
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }


}
