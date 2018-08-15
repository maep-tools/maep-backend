<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class month
 * @package App\Models
 * @version May 1, 2018, 6:06 pm UTC
 *
 * @property string name
 * @property string value
 */
class Month extends Model
{
    use SoftDeletes;

    public $table = 'months';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'value'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'value' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'value' => 'required'
    ];

    
}
