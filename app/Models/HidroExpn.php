<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\HidroConfig;
use App\Models\Horizont;

/**
 * Class HidroExpn
 * @package App\Models
 * @version April 6, 2018, 12:06 am UTC
 *
 * @property integer hidro_config_id
 * @property integer capacity
 * @property float prod_coefficient
 * @property float max_turbing_outflow
 * @property integer horizont_id
 * @property integer forced_unavailability
 * @property integer forced_unavailability
 * @property integer max_storage
 */
class HidroExpn extends Model
{
    public $table = 'hidro_expns';
    
    public function HydroConfig () {
        return $this->hasOne(HidroConfig::class, 'id', 'hidro_config_id');
    }

    public function horizont() {
        return $this->belongsTo(Horizont::class, 'horizont_id');
    }      

    public $fillable = [
        'hidro_config_id',
        'capacity',
        'prod_coefficient',
        'max_turbing_outflow',
        'horizont_id',
        'forced_unavailability',
        'historic_unavailability',
        'max_storage',
        'process_id',
        'emision' => 'float'        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'hidro_config_id' => 'integer',
        'capacity' => 'integer',
        'prod_coefficient' => 'float',
        'max_turbing_outflow' => 'float',
        'horizont_id' => 'integer',
        'forced_unavailability' => 'integer',
        'historic_unavailability' => 'integer',
        'max_storage' => 'integer',
        'process_id' => 'integer',
        'emision' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'hidro_config_id' => 'required',
        'capacity' => 'required',
        'prod_coefficient' => 'required',
        'max_turbing_outflow' => 'required',
        'horizont_id' => 'required',
        'forced_unavailability' => 'required',
        'historic_unavailability' => 'required',
        'max_storage' => 'required',
        'process_id' => 'required'
    ];

    
}
