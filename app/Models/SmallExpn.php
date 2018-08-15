<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SmallConfig;
use App\Models\Horizont;

/**
 * Class SmallExpn
 * @package App\Models
 * @version April 5, 2018, 11:31 pm UTC
 *
 * @property integer small_config_id
 * @property string planta_menor
 * @property date horizont_id
 * @property float max
 * @property float forced_unavailability
 * @property float historic_unavailability
 */
class SmallExpn extends Model
{
    public $table = 'small_expns';
    
    public function SmallConfig () {
        return $this->hasOne(SmallConfig::class, 'id', 'small_config_id');
    }

    public function horizont() {
        return $this->belongsTo(Horizont::class, 'horizont_id');
    }   


    protected $dates = ['deleted_at'];


    public $fillable = [
        'small_config_id',
        'process_id',
        'horizont_id',
        'max',
        'forced_unavailability',
        'historic_unavailability'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'small_config_id' => 'integer',
        'process_id' => 'integer',
        'horizont_id' => 'integer',
        'max' => 'float',
        'forced_unavailability' => 'float',
        'historic_unavailability' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'small_config_id' => 'required',
        'process_id' => 'integer',
        'horizont_id' => 'required',
        'max' => 'required',
        'forced_unavailability' => 'required',
        'historic_unavailability' => 'required'
    ];

    
}
