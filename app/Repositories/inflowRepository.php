<?php

namespace App\Repositories;

use App\Models\inflow;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class inflowRepository
 * @package App\Repositories
 * @version April 6, 2018, 12:09 am UTC
 *
 * @method inflow findWithoutFail($id, $columns = ['*'])
 * @method inflow find($id, $columns = ['*'])
 * @method inflow first($columns = ['*'])
*/
class inflowRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'scenario',
        'horizont_id',
        'hidro_config_id',
        'value'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return inflow::class;
    }
}
