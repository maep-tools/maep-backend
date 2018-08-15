<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\Areas;
/**
 * Class linesExpansion
 * @package App\Models
 * @version April 30, 2018, 2:28 pm UTC
 *
 * @property integer a_initial
 * @property integer a_final
 * @property integer stage
 * @property integer a_b
 * @property integer b_ai
 * @property float efficiency
 * @property float resistence
 * @property float reactance
 */
class LinesExpansion extends Model
{
    public $table = 'lines_expansions';
    
    public function initial () {
        return $this->hasOne(Areas::class, 'id', 'a_initial');
    }

    public function final () {
        return $this->hasOne(Areas::class, 'id', 'a_final');
    }
 
    public function horizont() {
        return $this->belongsTo(Horizont::class, 'stage');
    }   


    public $fillable = [
        'a_initial',
        'a_final',
        'stage',
        'line_id',
        'a_b',
        'b_ai',
        'efficiency',
        'resistence',
        'reactance',
        'process_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'a_initial' => 'integer',
        'a_final' => 'integer',
        'stage' => 'integer',
        'a_b' => 'double',
        'updated_at' => 'date',
        'b_ai' => 'double',
        'efficiency' => 'float',
        'resistence' => 'float',
        'reactance' => 'float',
        'process_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'stage' => 'required',
        'a_b' => 'required',
        'b_ai' => 'required',
        'efficiency' => 'required',
        'resistence' => 'required',
        'reactance' => 'required',
        'process_id' => 'required'
    ];

    
}
