<?php
/**
 * Created by PhpStorm.
 * User: alirzayev
 * Date: 20/01/2017
 * Time: 18:48
 */

namespace App\Gelsin\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchAdress extends Model
{
    use SoftDeletes;

    protected $table = 'br_addresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['street_name', 'street_line_extra', 'branch_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
        'updated_at',
        'created_at',
        'street_line_extra',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, "branch_id");
    }

}