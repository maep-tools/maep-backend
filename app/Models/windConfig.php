<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Areas;
use App\Models\EntranceStages;


/**
 * Class windConfig
 * @package App\Models
 * @version April 6, 2018, 12:14 am UTC
 *
 * @property string planta
 * @property integer capacity
 * @property float losses
 * @property float density
 * @property float efficiency
 * @property float diameter
 * @property integer speed_rated
 * @property integer entrance_stage_id
 * @property integer initial_storage_stage
 * @property integer area_id
 * @property integer forced_unavailability
 * @property integer variability
 * @property float speed_in
 * @property float speed_out
 * @property float betz_limit
 */
class WindConfig extends Model
{
    public $table = 'wind_configs';

    public function areas () {
        return $this->hasOne(Areas::class, 'id', 'area_id');
    }

    public function entranceStages () {
        return $this->hasOne(EntranceStages::class, 'id', 'entrance_stage_id');
    }

    protected $dates = ['deleted_at'];


    public $fillable = [
        'planta',
        'capacity',
        'losses',
        'density',
        'efficiency',
        'diameter',
        'speed_rated',
        'entrance_stage_id',
        'initial_storage_stage',
        'entrance_stage_date',
        'area_id',
        'forced_unavailability',
        'variability',
        'speed_in',
        'speed_out',
        'betz_limit',
        'process_id',
        'fp',
        'p_instalada'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'planta' => 'string',
        'capacity' => 'integer',
        'losses' => 'float',
        'process_id' => 'integer',
        'density' => 'float',
        'efficiency' => 'float',
        'diameter' => 'float',
        'speed_rated' => 'integer',
        'entrance_stage_id' => 'integer',
        'initial_storage_stage' => 'integer',
        'area_id' => 'integer',
        'forced_unavailability' => 'integer',
        'variability' => 'integer',
        'speed_in' => 'float',
        'speed_out' => 'float',
        'betz_limit' => 'float',
        'entrance_stage_date' => 'integer',
        'p_instalada' => 'float',
        'fp' => 'float' 
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'process_id' => 'required',
        'planta' => 'required',
        'capacity' => 'required',
        'losses' => 'required',
        'density' => 'required',
        'efficiency' => 'required',
        'diameter' => 'required',
        'speed_rated' => 'required',
        'entrance_stage_id' => 'required',
        'area_id' => 'required',
        'forced_unavailability' => 'required',
        'variability' => 'required',
        'speed_in' => 'required',
        'speed_out' => 'required',
        'betz_limit' => 'required'
    ];

    
}
