<?php

namespace App\Repositories;

use App\Models\horizont;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class horizontRepository
 * @package App\Repositories
 * @version April 10, 2018, 2:31 am UTC
 *
 * @method horizont findWithoutFail($id, $columns = ['*'])
 * @method horizont find($id, $columns = ['*'])
 * @method horizont first($columns = ['*'])
*/
class horizontRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'process_id',
        'period',
        'national'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return horizont::class;
    }
}
