<?php

use Faker\Factory as Faker;
use App\Models\SmallExpn;
use App\Repositories\SmallExpnRepository;

trait MakeSmallExpnTrait
{
    /**
     * Create fake instance of SmallExpn and save it in database
     *
     * @param array $smallExpnFields
     * @return SmallExpn
     */
    public function makeSmallExpn($smallExpnFields = [])
    {
        /** @var SmallExpnRepository $smallExpnRepo */
        $smallExpnRepo = App::make(SmallExpnRepository::class);
        $theme = $this->fakeSmallExpnData($smallExpnFields);
        return $smallExpnRepo->create($theme);
    }

    /**
     * Get fake instance of SmallExpn
     *
     * @param array $smallExpnFields
     * @return SmallExpn
     */
    public function fakeSmallExpn($smallExpnFields = [])
    {
        return new SmallExpn($this->fakeSmallExpnData($smallExpnFields));
    }

    /**
     * Get fake data of SmallExpn
     *
     * @param array $postFields
     * @return array
     */
    public function fakeSmallExpnData($smallExpnFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'small_config_id' => $fake->randomDigitNotNull,
            'planta_menor' => $fake->word,
            'modification' => $fake->word,
            'max' => $fake->randomDigitNotNull,
            'forced_unavailability' => $fake->randomDigitNotNull,
            'historic_unavailability' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $smallExpnFields);
    }
}
