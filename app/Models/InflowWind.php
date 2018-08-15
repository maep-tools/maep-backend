<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class inflowWind
 * @package App\Models
 * @version April 24, 2018, 12:04 am UTC
 *
 * @property integer horizont_id
 * @property integer scenario_id
 * @property integer wind_config_id
 * @property float value
 * @property integer process_id
 */
class InflowWind extends Model
{

    public $table = 'inflow_winds';
    


    public $fillable = [
        'horizont_id',
        'scenario_id',
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
        'scenario_id' => 'integer',
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
        'scenario_id' => 'required',
        'wind_config_id' => 'required',
        'value' => 'required',
        'process_id' => 'required'
    ];

    
}
