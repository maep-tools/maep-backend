<?php

namespace App\Repositories;

use App\Models\SmallConfig;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SmallConfigRepository
 * @package App\Repositories
 * @version April 5, 2018, 11:27 pm UTC
 *
 * @method SmallConfig findWithoutFail($id, $columns = ['*'])
 * @method SmallConfig find($id, $columns = ['*'])
 * @method SmallConfig first($columns = ['*'])
*/
class SmallConfigRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'planta_menor',
        'max',
        'entrance_stage_id',
        'type_id',
        'area_id',
        'gen_min',
        'gen_max',
        'forced_unavailability',
        'historic_unavailability'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return SmallConfig::class;
    }
}
