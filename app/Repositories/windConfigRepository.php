<?php

namespace App\Repositories;

use App\Models\windConfig;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class windConfigRepository
 * @package App\Repositories
 * @version April 6, 2018, 12:14 am UTC
 *
 * @method windConfig findWithoutFail($id, $columns = ['*'])
 * @method windConfig find($id, $columns = ['*'])
 * @method windConfig first($columns = ['*'])
*/
class windConfigRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'planta',
        'capacity',
        'losses',
        'density',
        'efficiency',
        'diameter',
        'speed_rated',
        'entrance_stage_id',
        'initial_storage_stage',
        'area_id',
        'forced_unavailability',
        'variability',
        'speed_in',
        'speed_out',
        'betz_limit'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return windConfig::class;
    }
}
