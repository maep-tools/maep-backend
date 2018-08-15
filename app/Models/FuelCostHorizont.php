<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FuelCostHorizont
 * @package App\Models
 * @version April 5, 2018, 10:48 pm UTC
 *
 * @property date horizont
 * @property integer horizont_id
 */
class FuelCostHorizont extends Model
{

    public $table = 'fuel_cost_horizonts';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'value',
        'horizont_id',
        'planta_fuel_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'float',
        'horizont_id' => 'integer',
        'planta_fuel_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'value' => 'required',
        'horizont_id' => 'required',
        'planta_fuel_id' => 'required'
    ];

    
}
