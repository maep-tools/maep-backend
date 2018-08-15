<?php

use Faker\Factory as Faker;
use App\Models\SmallConfig;
use App\Repositories\SmallConfigRepository;

trait MakeSmallConfigTrait
{
    /**
     * Create fake instance of SmallConfig and save it in database
     *
     * @param array $smallConfigFields
     * @return SmallConfig
     */
    public function makeSmallConfig($smallConfigFields = [])
    {
        /** @var SmallConfigRepository $smallConfigRepo */
        $smallConfigRepo = App::make(SmallConfigRepository::class);
        $theme = $this->fakeSmallConfigData($smallConfigFields);
        return $smallConfigRepo->create($theme);
    }

    /**
     * Get fake instance of SmallConfig
     *
     * @param array $smallConfigFields
     * @return SmallConfig
     */
    public function fakeSmallConfig($smallConfigFields = [])
    {
        return new SmallConfig($this->fakeSmallConfigData($smallConfigFields));
    }

    /**
     * Get fake data of SmallConfig
     *
     * @param array $postFields
     * @return array
     */
    public function fakeSmallConfigData($smallConfigFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'planta_menor' => $fake->word,
            'max' => $fake->randomDigitNotNull,
            'entrance_stage_id' => $fake->randomDigitNotNull,
            'type_id' => $fake->randomDigitNotNull,
            'area_id' => $fake->randomDigitNotNull,
            'gen_min' => $fake->randomDigitNotNull,
            'gen_max' => $fake->randomDigitNotNull,
            'forced_unavailability' => $fake->randomDigitNotNull,
            'historic_unavailability' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $smallConfigFields);
    }
}
