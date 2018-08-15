<?php

namespace App\Repositories;

use App\Models\month;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class monthRepository
 * @package App\Repositories
 * @version May 1, 2018, 6:06 pm UTC
 *
 * @method month findWithoutFail($id, $columns = ['*'])
 * @method month find($id, $columns = ['*'])
 * @method month first($columns = ['*'])
*/
class monthRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'value'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return month::class;
    }
}
