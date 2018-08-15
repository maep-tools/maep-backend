<?php

use Faker\Factory as Faker;
use App\Models\FuelCostPlant;
use App\Repositories\FuelCostPlantRepository;

trait MakeFuelCostPlantTrait
{
    /**
     * Create fake instance of FuelCostPlant and save it in database
     *
     * @param array $fuelCostPlantFields
     * @return FuelCostPlant
     */
    public function makeFuelCostPlant($fuelCostPlantFields = [])
    {
        /** @var FuelCostPlantRepository $fuelCostPlantRepo */
        $fuelCostPlantRepo = App::make(FuelCostPlantRepository::class);
        $theme = $this->fakeFuelCostPlantData($fuelCostPlantFields);
        return $fuelCostPlantRepo->create($theme);
    }

    /**
     * Get fake instance of FuelCostPlant
     *
     * @param array $fuelCostPlantFields
     * @return FuelCostPlant
     */
    public function fakeFuelCostPlant($fuelCostPlantFields = [])
    {
        return new FuelCostPlant($this->fakeFuelCostPlantData($fuelCostPlantFields));
    }

    /**
     * Get fake data of FuelCostPlant
     *
     * @param array $postFields
     * @return array
     */
    public function fakeFuelCostPlantData($fuelCostPlantFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $fuelCostPlantFields);
    }
}
