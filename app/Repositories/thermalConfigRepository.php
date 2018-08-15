<?php

namespace App\Repositories;

use App\Models\thermalConfig;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class thermalConfigRepository
 * @package App\Repositories
 * @version April 5, 2018, 11:00 pm UTC
 *
 * @method thermalConfig findWithoutFail($id, $columns = ['*'])
 * @method thermalConfig find($id, $columns = ['*'])
 * @method thermalConfig first($columns = ['*'])
*/
class thermalConfigRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'capacity',
        'entrance_stage_id',
        'type_id',
        'area_id',
        'planta_fuel_id',
        'gen_min',
        'gen_max',
        'forced_unavailability',
        'historic_unavailability',
        'O&MVariable',
        'MBTU_MWh'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return thermalConfig::class;
    }
}
