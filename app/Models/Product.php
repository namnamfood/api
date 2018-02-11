<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'quantity', 'branch_id', 'category_id', 'brand_id', 'cover'];

    /**
     * Get only discounted products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDiscounted($query)
    {
        return $query->where('discounted_price', '!=', null);
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }
}