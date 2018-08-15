<?php

namespace App\Repositories;

use App\Models\inflowWindM2;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class inflowWindM2Repository
 * @package App\Repositories
 * @version April 24, 2018, 12:24 am UTC
 *
 * @method inflowWindM2 findWithoutFail($id, $columns = ['*'])
 * @method inflowWindM2 find($id, $columns = ['*'])
 * @method inflowWindM2 first($columns = ['*'])
*/
class inflowWindM2Repository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fat'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return inflowWindM2::class;
    }
}
