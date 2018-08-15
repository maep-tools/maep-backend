<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\WindConfig;
use App\Models\Horizont;

/**
 * Class windExpn
 * @package App\Models
 * @version April 6, 2018, 12:18 am UTC
 *
 * @property integer wind_config_id
 * @property date horizont_id
 * @property integer capacity
 * @property float efficiency
 * @property integer number_turbines
 * @property float forced_unavailability
 * @property float historic_unavailability
 * @property float losses
 */
class WindExpn extends Model
{
    public $table = 'wind_expns';
    
    public function WindConfig () {
        return $this->hasOne(WindConfig::class, 'id', 'wind_config_id');
    }

    public function horizont() {
        return $this->belongsTo(Horizont::class, 'horizont_id');
    }   

    public $fillable = [
        'wind_config_id',
        'horizont_id',
        'capacity',
        'efficiency',
        'number_turbines',
        'forced_unavailability',
        'historic_unavailability',
        'losses',
        'process_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'wind_config_id' => 'integer',
        'horizont_id' => 'integer',
        'capacity' => 'integer',
        'efficiency' => 'float',
        'number_turbines' => 'integer',
        'forced_unavailability' => 'float',
        'historic_unavailability' => 'float',
        'losses' => 'float',
        'process_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'wind_config_id' => 'required',
        'horizont_id' => 'required',
        'capacity' => 'required',
        'efficiency' => 'required',
        'number_turbines' => 'required',
        'forced_unavailability' => 'required',
        'historic_unavailability' => 'required',
        'losses' => 'required',
        'process_id' => 'required'
    ];

    
}
