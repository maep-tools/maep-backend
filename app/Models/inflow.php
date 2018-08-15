<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class inflow
 * @package App\Models
 * @version April 6, 2018, 12:09 am UTC
 *
 * @property integer scenario_id
 * @property integer horizont_id
 * @property integer hidro_config_id
 * @property float value
 */
class Inflow extends Model
{
    public $table = 'inflows';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'scenario_id',
        'horizont_id',
        'process_id',
        'hidro_config_id',
        'value'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'scenario_id' => 'integer',
        'horizont_id' => 'integer',
        'hidro_config_id' => 'integer',
        'value' => 'float',
        'process_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'scenario_id' => 'required',
        'horizont_id' => 'required',
        'hidro_config_id' => 'required',
        'value' => 'required',
        'process_id' => 'required'
    ];

    
}
