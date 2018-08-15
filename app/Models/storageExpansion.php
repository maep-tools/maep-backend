<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\storage_config;
use App\Models\Horizont;
/**
 * Class storageExpansion
 * @package App\Models
 * @version April 30, 2018, 3:37 pm UTC
 *
 * @property integer storage_config_id
 * @property date horizont_id
 * @property float capacity
 * @property float efficiency
 * @property float max_outflow
 * @property unavailability forced
 * @property float historic_unavailability
 */
class StorageExpansion extends Model
{
    public $table = 'storage_expansions';
 
    public function StorageConfig () {
        return $this->hasOne(storage_config::class, 'id', 'storage_config_id');
    }

    public function horizont() {
        return $this->belongsTo(Horizont::class, 'horizont_id');
    }   


    public $fillable = [
        'process_id',
        'storage_config_id',
        'horizont_id',
        'capacity',
        'efficiency',
        'max_outflow',
        'forced_unavailability',
        'historic_unavailability'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'process_id' => 'integer',
        'storage_config_id' => 'integer',
        'horizont_id' => 'integer',
        'capacity' => 'float',
        'efficiency' => 'float',
        'max_outflow' => 'float',
        'historic_unavailability' => 'float',
        'forced_unavailability' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'process_id' => 'required',
        'storage_config_id' => 'required',
        'horizont_id' => 'required',
        'capacity' => 'required',
        'efficiency' => 'required',
        'max_outflow' => 'required',
        'forced_unavailability' => 'required',
        'historic_unavailability' => 'required'
    ];

    
}
