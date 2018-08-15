<?php

namespace App\Repositories;

use App\Models\windExpn;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class windExpnRepository
 * @package App\Repositories
 * @version April 6, 2018, 12:18 am UTC
 *
 * @method windExpn findWithoutFail($id, $columns = ['*'])
 * @method windExpn find($id, $columns = ['*'])
 * @method windExpn first($columns = ['*'])
*/
class windExpnRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'wind_config_id',
        'modification',
        'capacity',
        'efficiency',
        'number_turbines',
        'forced_unavailability',
        'historic_unavailability',
        'losses'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return windExpn::class;
    }
}
