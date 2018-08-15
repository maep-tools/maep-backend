<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FuelCostPlant
 * @package App\Models
 * @version April 5, 2018, 10:41 pm UTC
 *
 * @property string name
 */
class FuelCostPlant extends Model
{
    public $table = 'fuel_cost_plants';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'process_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'process_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'process_id' => 'required'
    ];

    
}
