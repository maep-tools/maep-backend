<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Areas;
use App\Models\EntranceStages;
use App\Models\Blocks;
use App\Models\WindM2Config;
use App\Models\Month;
/**
 * Class SpeedIndicesM2
 * @package App\Models
 * @version April 24, 2018, 2:11 am UTC
 *
 * @property xd lol
 */
class SpeedIndicesM2 extends Model
{

    public $table = 'speed_indices_m2s';
    
    public function windConfig () {
        return $this->hasOne(WindM2Config::class, 'id', 'wind_config_id');
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
