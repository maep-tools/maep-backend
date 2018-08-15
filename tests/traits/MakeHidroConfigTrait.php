<?php

use Faker\Factory as Faker;
use App\Models\HidroConfig;
use App\Repositories\HidroConfigRepository;

trait MakeHidroConfigTrait
{
    /**
     * Create fake instance of HidroConfig and save it in database
     *
     * @param array $hidroConfigFields
     * @return HidroConfig
     */
    public function makeHidroConfig($hidroConfigFields = [])
    {
        /** @var HidroConfigRepository $hidroConfigRepo */
        $hidroConfigRepo = App::make(HidroConfigRepository::class);
        $theme = $this->fakeHidroConfigData($hidroConfigFields);
        return $hidroConfigRepo->create($theme);
    }

    /**
     * Get fake instance of HidroConfig
     *
     * @param array $hidroConfigFields
     * @return HidroConfig
     */
    public function fakeHidroConfig($hidroConfigFields = [])
    {
        return new HidroConfig($this->fakeHidroConfigData($hidroConfigFields));
    }

    /**
     * Get fake data of HidroConfig
     *
     * @param array $postFields
     * @return array
     */
    public function fakeHidroConfigData($hidroConfigFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'planta' => $fake->word,
            'initial_storage' => $fake->randomDigitNotNull,
            'min_storage' => $fake->randomDigitNotNull,
            'max_storage' => $fake->randomDigitNotNull,
            'capacity' => $fake->randomDigitNotNull,
            'prod_coefficient' => $fake->randomDigitNotNull,
            'max_turbining_outflow' => $fake->randomDigitNotNull,
            'entrance_stage_id' => $fake->randomDigitNotNull,
            'initial_storage_stage' => $fake->randomDigitNotNull,
            'O&M' => $fake->randomDigitNotNull,
            't_downstream_id' => $fake->randomDigitNotNull,
            's_downstream_id' => $fake->randomDigitNotNull,
            'area_id' => $fake->randomDigitNotNull,
            'type_id' => $fake->randomDigitNotNull,
            'forced_unavailability' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $hidroConfigFields);
    }
}
