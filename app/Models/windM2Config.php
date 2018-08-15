<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Areas;
use App\Models\EntranceStages;


/**
 * Class WindM2Config
 * @package App\Models
 * @version April 24, 2018, 12:11 am UTC
 *
 * @property integer rows
 */
class WindM2Config extends Model
{

    public $table = 'wind_m2_configs';
    

       public function areas () {
        return $this->hasOne(Areas::class, 'id', 'area_id');
    }

    public function entranceStages () {
        return $this->hasOne(EntranceStages::class, 'id', 'entrance_stage_id');
    }

    protected $dates = ['deleted_at'];


    public $fillable = [
        'nombre_planta',
        'capacity',
        'losses',
        'wSpeed_min',
        'wSpeed_max',
        'speed_resolution',
        'measuring_height',
        'hub_height',
        'adjustment',
        'density',
        'distance',
        'diameter',
        'area_id',
        'entrance_stage_id',
        'entrance_stage_date',        
        'speedDataMinutes',
        'process_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre_planta' => 'string',
        'capacity' => 'float',
        'losses' => 'float',
        'wSpeed_min'=> 'float',
        'wSpeed_max'=> 'float',
        'speed_resolution'=> 'float',
        'measuring_height'=> 'float',
        'hub_height'=> 'float',
        'adjustment'=> 'float',
        'density'=> 'float',
        'distance'=> 'float',
        'diameter'=> 'float',
        'area_id'=> 'integer',
        'entrance_stage_id'=> 'integer',
        'entrance_stage_date' => 'integer',        
        'speedDataMinutes' => 'integer',
        'process_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre_planta' => 'required',
        'capacity' => 'required',
        'losses' => 'required',
        'wSpeed_min'=> 'required',
        'wSpeed_max'=> 'required',
        'speed_resolution'=> 'required',
        'measuring_height'=> 'required',
        'hub_height'=> 'required',
        'adjustment'=> 'required',
        'density'=> 'required',
        'distance'=> 'required',
        'diameter'=> 'required',
        'area_id'=> 'required',
        'entrance_stage_id'=> 'required',
        'speedDataMinutes' => 'required',
        'process_id' => 'required'
    ];
    
}
