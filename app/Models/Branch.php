<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Branch extends Model
{
    protected $fillable = [
        'name', 'region_id', 'description', 'cover', 'latitude', 'longitude', 'open_time', 'close_time'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(BranchAddress::class);
    }

    public static function getByDistance($latitude, $longitude, $distance)
    {
        $results = DB::select(DB::raw('SELECT id, ( 3959 * acos( cos( radians(' . $latitude . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians(latitude) ) ) ) AS distance FROM branches HAVING distance < ' . $distance . ' ORDER BY distance'));

        return $results;
    }
}
