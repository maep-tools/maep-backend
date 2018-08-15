<?php

namespace App\Repositories;

use App\Models\speedIndices;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class speedIndicesRepository
 * @package App\Repositories
 * @version April 23, 2018, 4:30 pm UTC
 *
 * @method speedIndices findWithoutFail($id, $columns = ['*'])
 * @method speedIndices find($id, $columns = ['*'])
 * @method speedIndices first($columns = ['*'])
*/
class speedIndicesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'horizont_id',
        'wind_config_id',
        'block_id',
        'value'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return speedIndices::class;
    }
}
