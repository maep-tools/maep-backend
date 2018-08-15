<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Areas;
use App\Models\EntranceStages;
/**
 * Class storage_config
 * @package App\Models
 * @version April 19, 2018, 11:32 pm UTC
 *
 * @property string name
 * @property float initial_storage
 * @property float min_storage
 * @property integer max_storage
 * @property float capacity
 * @property float efficiency
 * @property float max_outflow
 * @property date entrance_stage_date
 * @property float linked
 * @property string portfolio
 * @property integer area_id
 * @property integer process_id
 * @property float forced_unavailability
 * @property float historic_unavailability
 * @property float power_rate
 */
class Storage_config extends Model
{
    public $table = 'storage_configs';
    
    public function areas () {
        return $this->hasOne(Areas::class, 'id', 'area_id');
    }

    public function entranceStages () {
        return $this->hasOne(EntranceStages::class, 'id', 'entrance_stage_id');
    }

    public $fillable = [
        'name',
        'initial_storage',
        'min_storage',
        'max_storage',
        'capacity',
        'efficiency',
        'max_outflow',
        'entrance_stage_id',
        'entrance_stage_date',
        'linked',
        'portfolio',
        'area_id',
        'process_id',
        'forced_unavailability',
        'historic_unavailability',
        'power_rate'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'initial_storage' => 'float',
        'min_storage' => 'float',
        'max_storage' => 'integer',
        'capacity' => 'float',
        'efficiency' => 'float',
        'max_outflow' => 'float',
        'entrance_stage_id' => 'integer',
        'entrance_stage_date' => 'integer',
        'linked' => 'float',
        'portfolio' => 'string',
        'area_id' => 'integer',
        'process_id' => 'integer',
        'forced_unavailability' => 'float',
        'historic_unavailability' => 'float',
        'power_rate' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'entrance_stage_id' => 'required',
        'initial_storage' => 'required',
        'min_storage' => 'required',
        'max_storage' => 'required',
        'capacity' => 'required',
        'efficiency' => 'required',
        'max_outflow' => 'required',
        'linked' => 'required',
        'area_id' => 'required',
        'process_id' => 'required',
        'forced_unavailability' => 'required',
        'historic_unavailability' => 'required',
        'power_rate' => 'required'
    ];

    
}
