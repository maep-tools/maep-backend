<?php

use Faker\Factory as Faker;
use App\Models\speedIndices;
use App\Repositories\speedIndicesRepository;

trait MakespeedIndicesTrait
{
    /**
     * Create fake instance of speedIndices and save it in database
     *
     * @param array $speedIndicesFields
     * @return speedIndices
     */
    public function makespeedIndices($speedIndicesFields = [])
    {
        /** @var speedIndicesRepository $speedIndicesRepo */
        $speedIndicesRepo = App::make(speedIndicesRepository::class);
        $theme = $this->fakespeedIndicesData($speedIndicesFields);
        return $speedIndicesRepo->create($theme);
    }

    /**
     * Get fake instance of speedIndices
     *
     * @param array $speedIndicesFields
     * @return speedIndices
     */
    public function fakespeedIndices($speedIndicesFields = [])
    {
        return new speedIndices($this->fakespeedIndicesData($speedIndicesFields));
    }

    /**
     * Get fake data of speedIndices
     *
     * @param array $postFields
     * @return array
     */
    public function fakespeedIndicesData($speedIndicesFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'horizont_id' => $fake->randomDigitNotNull,
            'wind_config_id' => $fake->randomDigitNotNull,
            'block_id' => $fake->randomDigitNotNull,
            'value' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $speedIndicesFields);
    }
}
