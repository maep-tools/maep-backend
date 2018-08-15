<?php

namespace App\Repositories;

use App\Models\type;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class typeRepository
 * @package App\Repositories
 * @version April 5, 2018, 10:52 pm UTC
 *
 * @method type findWithoutFail($id, $columns = ['*'])
 * @method type find($id, $columns = ['*'])
 * @method type first($columns = ['*'])
*/
class typeRepository extends BaseRepository
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
        return type::class;
    }
}
