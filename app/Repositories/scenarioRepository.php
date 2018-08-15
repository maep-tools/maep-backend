<?php

namespace App\Repositories;

use App\Models\scenario;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class scenarioRepository
 * @package App\Repositories
 * @version April 23, 2018, 11:54 pm UTC
 *
 * @method scenario findWithoutFail($id, $columns = ['*'])
 * @method scenario find($id, $columns = ['*'])
 * @method scenario first($columns = ['*'])
*/
class scenarioRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return scenario::class;
    }
}
