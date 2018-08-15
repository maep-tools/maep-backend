<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class segment
 * @package App\Models
 * @version April 11, 2018, 11:02 pm UTC
 *
 * @property string name
 * @property integer process_id
 */
class Segment extends Model
{

    public $table = 'segments';
    

    protected $dates = ['deleted_at'];


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
