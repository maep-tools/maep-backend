<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Areas;
use App\Models\EntranceStages;
use App\Models\Type;
use App\Models\FuelCostPlant;

/**
 * Class thermalConfig
 * @package App\Models
 * @version April 5, 2018, 11:00 pm UTC
 *
 * @property integer capacity
 * @property integer entrance_stage_id
 * @property integer type_id
 * @property integer area_id
 * @property integer planta_fuel_id
 * @property float gen_min
 * @property float gen_max
 * @property float forced_unavailability
 * @property float historic_unavailability
 * @property float O&MVariable
 * @property float heat_rate
 */
class ThermalConfig extends Model
{

    public $table = 'thermal_configs';
    
    public function areas () {
        return $this->hasOne(Areas::class, 'id', 'area_id');
    }

    public function entranceStages () {
        return $this->hasOne(EntranceStages::class, 'id', 'entrance_stage_id');
    }

    public function type () {
        return $this->hasOne(Type::class, 'id', 'type_id');
    }

    public function fuel () {
        return $this->hasOne(FuelCostPlant::class, 'id', 'planta_fuel_id');
    }


    protected $dates = ['deleted_at'];


    public $fillable = [
        'capacity',
        'entrance_stage_id',
        'entrance_stage_date',
        'type_id',
        'area_id',
        'planta_fuel_id',
        'gen_min',
        'gen_max',
        'forced_unavailability',
        'historic_unavailability',
        'O&MVariable',
        'heat_rate',
        'process_id',
        'name',
        'emision'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'capacity' => 'float',
        'entrance_stage_id' => 'integer',
        'entrance_stage_date' => 'integer',
        'type_id' => 'integer',
        'area_id' => 'integer',
        'planta_fuel_id' => 'integer',
        'gen_min' => 'float',
        'gen_max' => 'float',
        'forced_unavailability' => 'float',
        'historic_unavailability' => 'float',
        'O&MVariable' => 'float',
        'heat_rate' => 'float',
        'process_id' => 'integer',
        'name' => 'string',
        'emision' => 'float'    
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'capacity' => 'required',
        'process_id' => 'required',        
        'entrance_stage_id' => 'required',
        'type_id' => 'required',
        'area_id' => 'required',
        'planta_fuel_id' => 'required',
        'gen_min' => 'required',
        'gen_max' => 'required',
        'forced_unavailability' => 'required',
        'historic_unavailability' => 'required',
        'O&MVariable' => 'required',
        'heat_rate' => 'required',
        'name' => 'required',
        'emision' => 'required'
    ];

    
}
