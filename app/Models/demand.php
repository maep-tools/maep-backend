<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class demand
 * @package App\Models
 * @version April 10, 2018, 2:37 am UTC
 *
 * @property integer horizont_id
 * @property integer area_id
 * @property float factor
 */
class Demand extends Model
{
    public $table = 'demands';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'horizont_id',
        'process_id',
        'area_id',
        'factor'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'horizont_id' => 'integer',
        'process_id' => 'integer',
        'area_id' => 'integer',
        'factor' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'horizont_id' => 'required',
        'process_id' => 'required',
        'area_id' => 'required',
        'factor' => 'required'
    ];

    
}
