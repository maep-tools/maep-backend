<?php

use Faker\Factory as Faker;
use App\Models\storage_config;
use App\Repositories\storage_configRepository;

trait Makestorage_configTrait
{
    /**
     * Create fake instance of storage_config and save it in database
     *
     * @param array $storageConfigFields
     * @return storage_config
     */
    public function makestorage_config($storageConfigFields = [])
    {
        /** @var storage_configRepository $storageConfigRepo */
        $storageConfigRepo = App::make(storage_configRepository::class);
        $theme = $this->fakestorage_configData($storageConfigFields);
        return $storageConfigRepo->create($theme);
    }

    /**
     * Get fake instance of storage_config
     *
     * @param array $storageConfigFields
     * @return storage_config
     */
    public function fakestorage_config($storageConfigFields = [])
    {
        return new storage_config($this->fakestorage_configData($storageConfigFields));
    }

    /**
     * Get fake data of storage_config
     *
     * @param array $postFields
     * @return array
     */
    public function fakestorage_configData($storageConfigFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'initial_storage' => $fake->randomDigitNotNull,
            'min_storage' => $fake->randomDigitNotNull,
            'max_storage' => $fake->randomDigitNotNull,
            'capacity' => $fake->randomDigitNotNull,
            'efficiency' => $fake->randomDigitNotNull,
            'max_outflow' => $fake->randomDigitNotNull,
            'entrance_stage_date' => $fake->word,
            'linked' => $fake->randomDigitNotNull,
            'portfolio' => $fake->word,
            'area_id' => $fake->randomDigitNotNull,
            'process_id' => $fake->randomDigitNotNull,
            'forced_unavailability' => $fake->randomDigitNotNull,
            'historic_unavailability' => $fake->randomDigitNotNull,
            'power_rate' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $storageConfigFields);
    }
}
