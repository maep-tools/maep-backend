<?php

namespace App\Repositories;

use App\Models\FuelCostHorizont;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class FuelCostHorizontRepository
 * @package App\Repositories
 * @version April 5, 2018, 10:48 pm UTC
 *
 * @method FuelCostHorizont findWithoutFail($id, $columns = ['*'])
 * @method FuelCostHorizont find($id, $columns = ['*'])
 * @method FuelCostHorizont first($columns = ['*'])
*/
class FuelCostHorizontRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'horizont',
        'planta_fuel_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FuelCostHorizont::class;
    }
}
