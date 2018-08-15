<?php

use Faker\Factory as Faker;
use App\Models\thermalConfig;
use App\Repositories\thermalConfigRepository;

trait MakethermalConfigTrait
{
    /**
     * Create fake instance of thermalConfig and save it in database
     *
     * @param array $thermalConfigFields
     * @return thermalConfig
     */
    public function makethermalConfig($thermalConfigFields = [])
    {
        /** @var thermalConfigRepository $thermalConfigRepo */
        $thermalConfigRepo = App::make(thermalConfigRepository::class);
        $theme = $this->fakethermalConfigData($thermalConfigFields);
        return $thermalConfigRepo->create($theme);
    }

    /**
     * Get fake instance of thermalConfig
     *
     * @param array $thermalConfigFields
     * @return thermalConfig
     */
    public function fakethermalConfig($thermalConfigFields = [])
    {
        return new thermalConfig($this->fakethermalConfigData($thermalConfigFields));
    }

    /**
     * Get fake data of thermalConfig
     *
     * @param array $postFields
     * @return array
     */
    public function fakethermalConfigData($thermalConfigFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'capacity' => $fake->randomDigitNotNull,
            'entrance_stage_id' => $fake->randomDigitNotNull,
            'type_id' => $fake->randomDigitNotNull,
            'area_id' => $fake->randomDigitNotNull,
            'planta_fuel_id' => $fake->randomDigitNotNull,
            'gen_min' => $fake->randomDigitNotNull,
            'gen_max' => $fake->randomDigitNotNull,
            'forced_unavailability' => $fake->randomDigitNotNull,
            'historic_unavailability' => $fake->randomDigitNotNull,
            'O&MVariable' => $fake->randomDigitNotNull,
            'MBTU_MWh' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $thermalConfigFields);
    }
}
