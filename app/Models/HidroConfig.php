<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Areas;
use App\Models\Type;
use App\Models\EntranceStages;
/**
 * Class HidroConfig
 * @package App\Models
 * @version April 6, 2018, 12:01 am UTC
 *
 * @property string planta
 * @property float initial_storage
 * @property float min_storage
 * @property float max_storage
 * @property integer capacity
 * @property float prod_coefficient
 * @property integer max_turbining_outflow
 * @property integer entrance_stage_id
 * @property integer initial_storage_stage
 * @property float O&M
 * @property integer t_downstream_id
 * @property integer s_downstream_id
 * @property integer area_id
 * @property integer type_id
 * @property float forced_unavailability
 */
class HidroConfig extends Model
{
    public $table = 'hidro_configs';
    

    protected $dates = ['deleted_at'];

    public function areas () {
        return $this->hasOne(Areas::class, 'id', 'area_id');
    }

    public function entranceStages () {
        return $this->hasOne(EntranceStages::class, 'id', 'entrance_stage_id');
    }

    public function type () {
        return $this->hasOne(Type::class, 'id', 'type_id');
    }

    public function downstream() {
        return $this->belongsTo(HidroConfig::class, 't_downstream_id');
    }      



    public $fillable = [
        'planta',
        'initial_storage',
        'min_storage',
        'max_storage',
        'capacity',
        'prod_coefficient',
        'max_turbining_outflow',
        'entrance_stage_id',
        'entrance_stage_date',
        'initial_storage_stage',
        'O&M',
        't_downstream_id',
        's_downstream_id',
        'area_id',
        'type_id',
        'forced_unavailability',
        'historic_unavailability',
        'process_id',
        'emision',
        'uploaded_series',
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
        'initial_storage' => 'float',
        'min_storage' => 'float',
        'max_storage' => 'float',
        'capacity' => 'integer',
        'prod_coefficient' => 'float',
        'max_turbining_outflow' => 'integer',
        'entrance_stage_id' => 'integer',
        'initial_storage_stage' => 'integer',
        'O&M' => 'float',
        't_downstream_id' => 'integer',
        's_downstream_id' => 'integer',
        'area_id' => 'integer',
        'type_id' => 'integer',
        'forced_unavailability' => 'float',
        'historic_unavailability' => 'float',
        'entrance_stage_date' => 'integer',
        'process_id' => 'integer',
        'emision' => 'float',
        'uploaded_series' => 'integer',
        'p_instalada' => 'float',
        'fp' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'planta' => 'required',
        'initial_storage' => 'required',
        'min_storage' => 'required',
        'max_storage' => 'required',
        'capacity' => 'required',
        'prod_coefficient' => 'required',
        'max_turbining_outflow' => 'required',
        'entrance_stage_id' => 'required',
        'initial_storage_stage' => 'required',
        'O&M' => 'required',
        'area_id' => 'required',
        'type_id' => 'required',
        'forced_unavailability' => 'required',
        'historic_unavailability' => 'required',
        'process_id' => 'required',
        'emision' => 'required'
    ];

    
}
