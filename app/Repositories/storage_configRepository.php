<?php

namespace App\Repositories;

use App\Models\storage_config;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class storage_configRepository
 * @package App\Repositories
 * @version April 19, 2018, 11:32 pm UTC
 *
 * @method storage_config findWithoutFail($id, $columns = ['*'])
 * @method storage_config find($id, $columns = ['*'])
 * @method storage_config first($columns = ['*'])
*/
class storage_configRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'initial_storage',
        'min_storage',
        'max_storage',
        'capacity',
        'efficiency',
        'max_outflow',
        'entrance_stage_date',
        'linked',
        'portfolio',
        'area_id',
        'process_id',
        'forced_unavailability',
        'historic_unavailability',
        'power_rate'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return storage_config::class;
    }
}
