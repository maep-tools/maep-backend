<?php

namespace App\Repositories;

use App\Models\demand;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class demandRepository
 * @package App\Repositories
 * @version April 10, 2018, 2:37 am UTC
 *
 * @method demand findWithoutFail($id, $columns = ['*'])
 * @method demand find($id, $columns = ['*'])
 * @method demand first($columns = ['*'])
*/
class demandRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'horizont_id',
        'area_id',
        'factor'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return demand::class;
    }
}
