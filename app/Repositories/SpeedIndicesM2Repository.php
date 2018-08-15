<?php

namespace App\Repositories;

use App\Models\SpeedIndicesM2;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SpeedIndicesM2Repository
 * @package App\Repositories
 * @version April 24, 2018, 2:11 am UTC
 *
 * @method SpeedIndicesM2 findWithoutFail($id, $columns = ['*'])
 * @method SpeedIndicesM2 find($id, $columns = ['*'])
 * @method SpeedIndicesM2 first($columns = ['*'])
*/
class SpeedIndicesM2Repository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lol'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return SpeedIndicesM2::class;
    }
}
