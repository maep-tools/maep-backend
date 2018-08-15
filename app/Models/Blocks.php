<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Blocks
 * @package App\Models
 * @version April 19, 2018, 9:36 pm UTC
 *
 * @property float duration
 * @property float participation
 * @property boolean storage_restrictions
 */
class Blocks extends Model
{
    public $table = 'blocks';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'duration',
        'participation',
        'storage_restrictions',
        'process_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'duration' => 'float',
        'participation' => 'float',
        'storage_restrictions' => 'boolean',
        'process_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'duration' => 'required',
        'participation' => 'required',
        'process_id' => 'integer'
    ];

    
}
