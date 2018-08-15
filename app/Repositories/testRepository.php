<?php

namespace App\Repositories;

use App\Models\test;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class testRepository
 * @package App\Repositories
 * @version March 1, 2018, 12:11 am UTC
 *
 * @method test findWithoutFail($id, $columns = ['*'])
 * @method test find($id, $columns = ['*'])
 * @method test first($columns = ['*'])
*/
class testRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return test::class;
    }
}
