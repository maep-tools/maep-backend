<?php

namespace App\Repositories;

use App\Models\SmallExpn;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SmallExpnRepository
 * @package App\Repositories
 * @version April 5, 2018, 11:31 pm UTC
 *
 * @method SmallExpn findWithoutFail($id, $columns = ['*'])
 * @method SmallExpn find($id, $columns = ['*'])
 * @method SmallExpn first($columns = ['*'])
*/
class SmallExpnRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'small_config_id',
        'planta_menor',
        'modification',
        'max',
        'forced_unavailability',
        'historic_unavailability'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return SmallExpn::class;
    }
}
