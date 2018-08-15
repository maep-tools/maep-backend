<?php

namespace App\Repositories;

use App\Models\RationingCost;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class RationingCostRepository
 * @package App\Repositories
 * @version April 5, 2018, 11:23 pm UTC
 *
 * @method RationingCost findWithoutFail($id, $columns = ['*'])
 * @method RationingCost find($id, $columns = ['*'])
 * @method RationingCost first($columns = ['*'])
*/
class RationingCostRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'horizont',
        'segment1'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RationingCost::class;
    }
}
