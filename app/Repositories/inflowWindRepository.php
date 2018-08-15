<?php

namespace App\Repositories;

use App\Models\inflowWind;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class inflowWindRepository
 * @package App\Repositories
 * @version April 24, 2018, 12:04 am UTC
 *
 * @method inflowWind findWithoutFail($id, $columns = ['*'])
 * @method inflowWind find($id, $columns = ['*'])
 * @method inflowWind first($columns = ['*'])
*/
class inflowWindRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'horizont_id',
        'scenario_id',
        'wind_config_id',
        'value',
        'process_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return inflowWind::class;
    }
}
