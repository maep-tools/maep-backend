<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Areas;
/**
 * Class lines
 * @package App\Models
 * @version April 30, 2018, 2:24 pm UTC
 *
 * @property integer a_initial
 * @property integer b_final
 * @property float a_to_b
 * @property float b_to_a
 * @property float efficiency
 * @property float resistence
 * @property float reactance
 */
class Lines extends Model
{
    public $table = 'lines';
    
    public function initial () {
        return $this->hasOne(Areas::class, 'id', 'a_initial');
    }

    public function final () {
        return $this->hasOne(Areas::class, 'id', 'b_final');
    }

    public $fillable = [
        'process_id',
        'a_initial',
        'b_final',
        'a_to_b',
        'b_to_a',
        'efficiency',
        'resistence',
        'reactance'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'process_id' => 'integer',        
        'a_initial' => 'integer',
        'b_final' => 'integer',
        'a_to_b' => 'float',
        'b_to_a' => 'float',
        'efficiency' => 'float',
        'resistence' => 'float',
        'reactance' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'process_id' => 'required',
        'a_to_b' => 'required',
        'b_to_a' => 'required',
        'efficiency' => 'required',
        'resistence' => 'required',
        'reactance' => 'required'
    ];

    
}
