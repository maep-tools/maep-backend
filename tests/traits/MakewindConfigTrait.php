<?php

use Faker\Factory as Faker;
use App\Models\windConfig;
use App\Repositories\windConfigRepository;

trait MakewindConfigTrait
{
    /**
     * Create fake instance of windConfig and save it in database
     *
     * @param array $windConfigFields
     * @return windConfig
     */
    public function makewindConfig($windConfigFields = [])
    {
        /** @var windConfigRepository $windConfigRepo */
        $windConfigRepo = App::make(windConfigRepository::class);
        $theme = $this->fakewindConfigData($windConfigFields);
        return $windConfigRepo->create($theme);
    }

    /**
     * Get fake instance of windConfig
     *
     * @param array $windConfigFields
     * @return windConfig
     */
    public function fakewindConfig($windConfigFields = [])
    {
        return new windConfig($this->fakewindConfigData($windConfigFields));
    }

    /**
     * Get fake data of windConfig
     *
     * @param array $postFields
     * @return array
     */
    public function fakewindConfigData($windConfigFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'planta' => $fake->word,
            'capacity' => $fake->randomDigitNotNull,
            'losses' => $fake->randomDigitNotNull,
            'density' => $fake->randomDigitNotNull,
            'efficiency' => $fake->randomDigitNotNull,
            'diameter' => $fake->randomDigitNotNull,
            'speed_rated' => $fake->randomDigitNotNull,
            'entrance_stage_id' => $fake->randomDigitNotNull,
            'initial_storage_stage' => $fake->randomDigitNotNull,
            'area_id' => $fake->randomDigitNotNull,
            'forced_unavailability' => $fake->randomDigitNotNull,
            'variability' => $fake->randomDigitNotNull,
            'speed_in' => $fake->randomDigitNotNull,
            'speed_out' => $fake->randomDigitNotNull,
            'betz_limit' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $windConfigFields);
    }
}
