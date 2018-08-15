<?php

namespace App\Repositories;

use App\Models\Blocks;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class BlocksRepository
 * @package App\Repositories
 * @version April 19, 2018, 9:36 pm UTC
 *
 * @method Blocks findWithoutFail($id, $columns = ['*'])
 * @method Blocks find($id, $columns = ['*'])
 * @method Blocks first($columns = ['*'])
*/
class BlocksRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'duration',
        'participation',
        'storage_restrictions'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Blocks::class;
    }
}
