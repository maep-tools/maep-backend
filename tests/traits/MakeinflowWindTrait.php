<?php

use Faker\Factory as Faker;
use App\Models\inflowWind;
use App\Repositories\inflowWindRepository;

trait MakeinflowWindTrait
{
    /**
     * Create fake instance of inflowWind and save it in database
     *
     * @param array $inflowWindFields
     * @return inflowWind
     */
    public function makeinflowWind($inflowWindFields = [])
    {
        /** @var inflowWindRepository $inflowWindRepo */
        $inflowWindRepo = App::make(inflowWindRepository::class);
        $theme = $this->fakeinflowWindData($inflowWindFields);
        return $inflowWindRepo->create($theme);
    }

    /**
     * Get fake instance of inflowWind
     *
     * @param array $inflowWindFields
     * @return inflowWind
     */
    public function fakeinflowWind($inflowWindFields = [])
    {
        return new inflowWind($this->fakeinflowWindData($inflowWindFields));
    }

    /**
     * Get fake data of inflowWind
     *
     * @param array $postFields
     * @return array
     */
    public function fakeinflowWindData($inflowWindFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'horizont_id' => $fake->randomDigitNotNull,
            'scenario_id' => $fake->randomDigitNotNull,
            'wind_config_id' => $fake->randomDigitNotNull,
            'value' => $fake->randomDigitNotNull,
            'process_id' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $inflowWindFields);
    }
}
