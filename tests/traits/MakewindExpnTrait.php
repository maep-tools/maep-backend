<?php

use Faker\Factory as Faker;
use App\Models\windExpn;
use App\Repositories\windExpnRepository;

trait MakewindExpnTrait
{
    /**
     * Create fake instance of windExpn and save it in database
     *
     * @param array $windExpnFields
     * @return windExpn
     */
    public function makewindExpn($windExpnFields = [])
    {
        /** @var windExpnRepository $windExpnRepo */
        $windExpnRepo = App::make(windExpnRepository::class);
        $theme = $this->fakewindExpnData($windExpnFields);
        return $windExpnRepo->create($theme);
    }

    /**
     * Get fake instance of windExpn
     *
     * @param array $windExpnFields
     * @return windExpn
     */
    public function fakewindExpn($windExpnFields = [])
    {
        return new windExpn($this->fakewindExpnData($windExpnFields));
    }

    /**
     * Get fake data of windExpn
     *
     * @param array $postFields
     * @return array
     */
    public function fakewindExpnData($windExpnFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'wind_config_id' => $fake->randomDigitNotNull,
            'modification' => $fake->word,
            'capacity' => $fake->randomDigitNotNull,
            'efficiency' => $fake->randomDigitNotNull,
            'number_turbines' => $fake->randomDigitNotNull,
            'forced_unavailability' => $fake->randomDigitNotNull,
            'historic_unavailability' => $fake->randomDigitNotNull,
            'losses' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $windExpnFields);
    }
}
