<?php

namespace App\Repositories;

use App\Models\storageExpansion;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class storageExpansionRepository
 * @package App\Repositories
 * @version April 30, 2018, 3:37 pm UTC
 *
 * @method storageExpansion findWithoutFail($id, $columns = ['*'])
 * @method storageExpansion find($id, $columns = ['*'])
 * @method storageExpansion first($columns = ['*'])
*/
class storageExpansionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'storage_config_id',
        'modification',
        'capacity',
        'efficiency',
        'max_outflow',
        'forced',
        'historic_unavailability'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return storageExpansion::class;
    }
}
