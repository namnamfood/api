<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchAddress extends Model
{
    protected $fillable = ['branch_id', 'street_id', 'packet_price',];

    public function street()
    {
        return $this->belongsTo(RegionStreet::class);

    }
}

