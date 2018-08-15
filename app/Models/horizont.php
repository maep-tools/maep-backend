<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class horizont
 * @package App\Models
 * @version April 10, 2018, 2:31 am UTC
 *
 * @property integer process_id
 * @property date period
 * @property float national
 */
class Horizont extends Model
{
    public $table = 'horizonts';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'process_id',
        'period',
        'national'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'process_id' => 'integer',
        'period' => 'date',
        'national' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'process_id' => 'required',
        'period' => 'required',
        'national' => 'required'
    ];


    
}
