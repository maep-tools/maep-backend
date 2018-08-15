<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Areas
 * @package App\Models
 * @version March 31, 2018, 3:38 pm UTC
 *
 * @property integer process_id
 * @property string name
 */
class Areas extends Model
{

    public $table = 'areas';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'process_id',
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'process_id' => 'integer',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
