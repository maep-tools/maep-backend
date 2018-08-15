<?php

use Faker\Factory as Faker;
use App\Models\FuelCostHorizont;
use App\Repositories\FuelCostHorizontRepository;

trait MakeFuelCostHorizontTrait
{
    /**
     * Create fake instance of FuelCostHorizont and save it in database
     *
     * @param array $fuelCostHorizontFields
     * @return FuelCostHorizont
     */
    public function makeFuelCostHorizont($fuelCostHorizontFields = [])
    {
        /** @var FuelCostHorizontRepository $fuelCostHorizontRepo */
        $fuelCostHorizontRepo = App::make(FuelCostHorizontRepository::class);
        $theme = $this->fakeFuelCostHorizontData($fuelCostHorizontFields);
        return $fuelCostHorizontRepo->create($theme);
    }

    /**
     * Get fake instance of FuelCostHorizont
     *
     * @param array $fuelCostHorizontFields
     * @return FuelCostHorizont
     */
    public function fakeFuelCostHorizont($fuelCostHorizontFields = [])
    {
        return new FuelCostHorizont($this->fakeFuelCostHorizontData($fuelCostHorizontFields));
    }

    /**
     * Get fake data of FuelCostHorizont
     *
     * @param array $postFields
     * @return array
     */
    public function fakeFuelCostHorizontData($fuelCostHorizontFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'horizont' => $fake->word,
            'planta_fuel_id' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $fuelCostHorizontFields);
    }
}
