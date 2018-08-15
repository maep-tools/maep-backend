<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Areas;
use App\Models\EntranceStages;
use App\Models\Type;


/**
 * Class SmallConfig
 * @package App\Models
 * @version April 5, 2018, 11:27 pm UTC
 *
 * @property stirng planta_menor
 * @property float max
 * @property integer entrance_stage_id
 * @property integer type_id
 * @property integer area_id
 * @property float gen_min
 * @property float gen_max
 * @property integer forced_unavailability
 * @property  historic_unavailability
 */
class SmallConfig extends Model
{
    public $table = 'small_configs';
    

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


    public $fillable = [
        'planta_menor',
        'max',
        'process_id',
        'entrance_stage_id',
        'entrance_stage_date',
        'type_id',
        'area_id',
        'gen_min',
        'gen_max',
        'forced_unavailability',
        'historic_unavailability'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'max' => 'float',
        'process_id' => 'integer',        
        'entrance_stage_id' => 'integer',
        'type_id' => 'integer',
        'area_id' => 'integer',
        'entrance_stage_date' => 'integer',        
        'gen_min' => 'float',
        'gen_max' => 'float',
        'forced_unavailability' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'process_id' => 'required',
        'planta_menor' => 'required',
        'max' => 'required',
        'entrance_stage_id' => 'required',
        'type_id' => 'required',
        'area_id' => 'required',
        'gen_min' => 'required',
        'gen_max' => 'required',
        'forced_unavailability' => 'required',
        'historic_unavailability' => 'required'
    ];

    
}
