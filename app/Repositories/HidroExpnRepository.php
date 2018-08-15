<?php

namespace App\Repositories;

use App\Models\HidroExpn;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class HidroExpnRepository
 * @package App\Repositories
 * @version April 6, 2018, 12:06 am UTC
 *
 * @method HidroExpn findWithoutFail($id, $columns = ['*'])
 * @method HidroExpn find($id, $columns = ['*'])
 * @method HidroExpn first($columns = ['*'])
*/
class HidroExpnRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'hidro_config_id',
        'capacity',
        'prod_coefficient',
        'max_turbing_outflow',
        'modification',
        'forced_unavailability',
        'forced_unavailability',
        'max_storage'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return HidroExpn::class;
    }
}
