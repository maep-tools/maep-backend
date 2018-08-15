<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class inflowWindM2
 * @package App\Models
 * @version April 24, 2018, 12:24 am UTC
 *
 * @property id fat
 */
class InflowWindM2 extends Model
{

    public $table = 'inflow_wind_m2s';
    

public $fillable = [
        'horizont_id',
        'scenario',
        'wind_config_id',
        'value',
        'process_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'horizont_id' => 'integer',
        'scenario' => 'integer',
        'wind_config_id' => 'integer',
        'value' => 'float',
        'process_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'horizont_id' => 'required',
        'scenario' => 'required',
        'wind_config_id' => 'required',
        'value' => 'required',
        'process_id' => 'required'
    ];
    
}
