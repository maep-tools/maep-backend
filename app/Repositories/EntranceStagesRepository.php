<?php

namespace App\Repositories;

use App\Models\EntranceStages;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class EntranceStagesRepository
 * @package App\Repositories
 * @version April 5, 2018, 10:50 pm UTC
 *
 * @method EntranceStages findWithoutFail($id, $columns = ['*'])
 * @method EntranceStages find($id, $columns = ['*'])
 * @method EntranceStages first($columns = ['*'])
*/
class EntranceStagesRepository extends BaseRepository
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
        return EntranceStages::class;
    }
}
