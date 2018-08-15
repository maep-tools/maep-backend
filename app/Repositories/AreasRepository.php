<?php

namespace App\Repositories;

use App\Models\Areas;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AreasRepository
 * @package App\Repositories
 * @version March 31, 2018, 3:38 pm UTC
 *
 * @method Areas findWithoutFail($id, $columns = ['*'])
 * @method Areas find($id, $columns = ['*'])
 * @method Areas first($columns = ['*'])
*/
class AreasRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'process_id',
        'name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Areas::class;
    }
}
