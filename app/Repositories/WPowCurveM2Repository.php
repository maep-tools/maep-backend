<?php

namespace App\Repositories;

use App\Models\WPowCurveM2;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class WPowCurveM2Repository
 * @package App\Repositories
 * @version April 24, 2018, 1:54 am UTC
 *
 * @method WPowCurveM2 findWithoutFail($id, $columns = ['*'])
 * @method WPowCurveM2 find($id, $columns = ['*'])
 * @method WPowCurveM2 first($columns = ['*'])
*/
class WPowCurveM2Repository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'p',
        'CT'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return WPowCurveM2::class;
    }
}
