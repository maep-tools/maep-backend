<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class scenario
 * @package App\Models
 * @version April 23, 2018, 11:54 pm UTC
 *
 * @property string name
 */
class Scenario extends Model
{
    public $table = 'scenarios';


    public $fillable = [
        'name',
        'process_id'        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'process_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'process_id' => 'required'
    ];

    
}
