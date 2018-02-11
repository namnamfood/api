<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $appends = ['cover_url'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'parent_id', 'cover'];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the parent category of given category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Scope a query to only include parent categories.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function scopeParents($query)
    {
        return $query->where('parent_id', 0)->get();
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get category products.
     *
     * @return string
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Get full url of cover image
     *
     * @return string
     */
    public function getCoverUrlAttribute()
    {
        return url('/api/images/categories/' . $this->id);
    }


    /**
     * Declare event handlers
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            // before delete() method call this
            $category->children()->delete();
        });
    }
}
