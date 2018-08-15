<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\WindConfig;
use App\Models\Blocks;
use App\Models\Horizont;
use App\Models\Month;

/**
 * Class speedIndices
 * @package App\Models
 * @version April 23, 2018, 4:30 pm UTC
 *
 * @property integer month_id
 * @property integer wind_config_id
 * @property integer block_id
 * @property float value
 */
class SpeedIndices extends Model
{

    public $table = 'speed_indices';
    

    public function windConfig () {
        return $this->hasOne(windConfig::class, 'id', 'wind_config_id');
    }

    public function blocks () {
        return $this->hasOne(Blocks::class, 'id', 'block_id');
    }


    public function horizont() {
        return $this->belongsTo(Month::class, 'month_id');
    }      

    public $fillable = [
        'month_id',
        'wind_config_id',
        'block_id',
        'value',
        'process_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'month_id' => 'integer',
        'wind_config_id' => 'integer',
        'block_id' => 'integer',
        'value' => 'float',
        'process_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'month_id' => 'required',
        'wind_config_id' => 'required',
        'block_id' => 'required',
        'value' => 'required',
        'process_id' => 'required'
    ];

    
}
