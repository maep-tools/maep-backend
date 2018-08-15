<?php

namespace App\Repositories;

use App\Models\process;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class processRepository
 * @package App\Repositories
 * @version March 1, 2018, 11:59 pm UTC
 *
 * @method process findWithoutFail($id, $columns = ['*'])
 * @method process find($id, $columns = ['*'])
 * @method process first($columns = ['*'])
*/
class processRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'statusId',
        'templateId',
        'max_iter',
        'extra_stages',
        'stages',
        'seriesBack',
        'seriesForw',
        'stochastic',
        'variance',
        'sensDem',
        'speed_out',
        'speed_in',
        'eps_area',
        'eps_all',
        'eps_risk',
        'commit',
        'lag_max',
        'testing_t',
        'd_correl',
        'seasonality'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return process::class;
    }
}
