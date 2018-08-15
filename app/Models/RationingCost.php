<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RationingCost
 * @package App\Models
 * @version April 5, 2018, 11:23 pm UTC
 *
 * @property date horizont
 * @property float segment1
 */
class RationingCost extends Model
{
    public $table = 'rationing_costs';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'horizont_id',
        'segment_id',
        'value',
        'process_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'horizont_id' => 'integer',
        'segment_id' => 'integer',
        'value' => 'float',
        'process_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'horizont_id' => 'required',
        'segment_id' => 'required',
        'value' => 'required',
        'process_id' => 'required'
    ];

    
}
