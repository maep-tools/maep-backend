<?php

namespace App\Repositories;

use App\Models\HidroConfig;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class HidroConfigRepository
 * @package App\Repositories
 * @version April 6, 2018, 12:01 am UTC
 *
 * @method HidroConfig findWithoutFail($id, $columns = ['*'])
 * @method HidroConfig find($id, $columns = ['*'])
 * @method HidroConfig first($columns = ['*'])
*/
class HidroConfigRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'planta',
        'initial_storage',
        'min_storage',
        'max_storage',
        'capacity',
        'prod_coefficient',
        'max_turbining_outflow',
        'entrance_stage_id',
        'initial_storage_stage',
        'O&M',
        't_downstream_id',
        's_downstream_id',
        'area_id',
        'type_id',
        'forced_unavailability'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return HidroConfig::class;
    }
}
