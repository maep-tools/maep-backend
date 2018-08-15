<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WPowCurveM2
 * @package App\Models
 * @version April 24, 2018, 1:54 am UTC
 *
 * @property float p
 * @property float CT
 */
class WPowCurveM2 extends Model
{
    public $table = 'w_pow_curve_m2s';
    

    public $fillable = [
        'p',
        'CT',
        'wind_m2_config_id',
        'TpR',
        'process_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'p' => 'float',
        'CT' => 'float',
        'TpR' => 'float',
        'wind_m2_config_id' => 'float',
        'process_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'p' => 'required',
        'CT' => 'required',
        'TpR' => 'required',
        'wind_m2_config_id' => 'required',
        'process_id' => 'required'
    ];

    
}
