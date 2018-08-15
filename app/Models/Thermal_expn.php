<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ThermalConfig;
use App\Models\Horizont;
/**
 * Class Thermal_expn
 * @package App\Models
 * @version April 5, 2018, 11:06 pm UTC
 *
 * @property integer thermal_config_id
 * @property date horizont_id
 * @property integer max
 * @property float gen_min
 * @property float gen_max
 * @property float forced_unavailability
 * @property float historic_unavailability
 */
class Thermal_expn extends Model
{

    public $table = 'thermal_expns';
    

    public function ThermalConfig () {
        return $this->hasOne(ThermalConfig::class, 'id', 'thermal_config_id');
    }

    public function horizont() {
        return $this->belongsTo(Horizont::class, 'horizont_id');
    }   

    public $fillable = [
        'thermal_config_id',
        'horizont_id',
        'max',
        'gen_min',
        'gen_max',
        'forced_unavailability',
        'historic_unavailability',
        'process_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'thermal_config_id' => 'integer',
        'horizont_id' => 'integer',
        'max' => 'integer',
        'gen_min' => 'float',
        'gen_max' => 'float',
        'forced_unavailability' => 'float',
        'historic_unavailability' => 'float',
        'process_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'thermal_config_id' => 'required',
        'horizont_id' => 'required',
        'max' => 'required',
        'gen_min' => 'required',
        'gen_max' => 'required',
        'forced_unavailability' => 'required',
        'historic_unavailability' => 'required',
        'process_id' => 'required'
    ];

    
}
