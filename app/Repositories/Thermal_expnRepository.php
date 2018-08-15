<?php

namespace App\Repositories;

use App\Models\Thermal_expn;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class Thermal_expnRepository
 * @package App\Repositories
 * @version April 5, 2018, 11:06 pm UTC
 *
 * @method Thermal_expn findWithoutFail($id, $columns = ['*'])
 * @method Thermal_expn find($id, $columns = ['*'])
 * @method Thermal_expn first($columns = ['*'])
*/
class Thermal_expnRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'thermal_config_id',
        'modification',
        'max',
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
        return Thermal_expn::class;
    }
}
