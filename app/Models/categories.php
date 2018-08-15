<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class categories
 * @package App\Models
 * @version March 20, 2018, 2:54 am UTC
 *
 * @property string name
 * @property integer parentId
 * @property string component
 * @property integer disabled
 */
class Categories extends Model
{
    use SoftDeletes;

    public $table = 'categories';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'parentId',
        'component',
        'disabled',
        'order'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'parentId' => 'integer',
        'component' => 'string',
        'disabled' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'parentId' => 'required',
        'component' => 'required',
        'disabled' => 'boolean'
    ];

    
}
