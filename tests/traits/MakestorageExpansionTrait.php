<?php

use Faker\Factory as Faker;
use App\Models\storageExpansion;
use App\Repositories\storageExpansionRepository;

trait MakestorageExpansionTrait
{
    /**
     * Create fake instance of storageExpansion and save it in database
     *
     * @param array $storageExpansionFields
     * @return storageExpansion
     */
    public function makestorageExpansion($storageExpansionFields = [])
    {
        /** @var storageExpansionRepository $storageExpansionRepo */
        $storageExpansionRepo = App::make(storageExpansionRepository::class);
        $theme = $this->fakestorageExpansionData($storageExpansionFields);
        return $storageExpansionRepo->create($theme);
    }

    /**
     * Get fake instance of storageExpansion
     *
     * @param array $storageExpansionFields
     * @return storageExpansion
     */
    public function fakestorageExpansion($storageExpansionFields = [])
    {
        return new storageExpansion($this->fakestorageExpansionData($storageExpansionFields));
    }

    /**
     * Get fake data of storageExpansion
     *
     * @param array $postFields
     * @return array
     */
    public function fakestorageExpansionData($storageExpansionFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'storage_config_id' => $fake->randomDigitNotNull,
            'modification' => $fake->word,
            'capacity' => $fake->randomDigitNotNull,
            'efficiency' => $fake->randomDigitNotNull,
            'max_outflow' => $fake->randomDigitNotNull,
            'forced' => $fake->word,
            'historic_unavailability' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $storageExpansionFields);
    }
}
