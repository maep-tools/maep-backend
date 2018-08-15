<?php

namespace App\Repositories;

use App\Models\FuelCostPlant;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class FuelCostPlantRepository
 * @package App\Repositories
 * @version April 5, 2018, 10:41 pm UTC
 *
 * @method FuelCostPlant findWithoutFail($id, $columns = ['*'])
 * @method FuelCostPlant find($id, $columns = ['*'])
 * @method FuelCostPlant first($columns = ['*'])
*/
class FuelCostPlantRepository extends BaseRepository
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
        return FuelCostPlant::class;
    }
}
