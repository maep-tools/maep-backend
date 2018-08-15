<?php

use Faker\Factory as Faker;
use App\Models\HidroExpn;
use App\Repositories\HidroExpnRepository;

trait MakeHidroExpnTrait
{
    /**
     * Create fake instance of HidroExpn and save it in database
     *
     * @param array $hidroExpnFields
     * @return HidroExpn
     */
    public function makeHidroExpn($hidroExpnFields = [])
    {
        /** @var HidroExpnRepository $hidroExpnRepo */
        $hidroExpnRepo = App::make(HidroExpnRepository::class);
        $theme = $this->fakeHidroExpnData($hidroExpnFields);
        return $hidroExpnRepo->create($theme);
    }

    /**
     * Get fake instance of HidroExpn
     *
     * @param array $hidroExpnFields
     * @return HidroExpn
     */
    public function fakeHidroExpn($hidroExpnFields = [])
    {
        return new HidroExpn($this->fakeHidroExpnData($hidroExpnFields));
    }

    /**
     * Get fake data of HidroExpn
     *
     * @param array $postFields
     * @return array
     */
    public function fakeHidroExpnData($hidroExpnFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'hidro_config_id' => $fake->randomDigitNotNull,
            'capacity' => $fake->randomDigitNotNull,
            'prod_coefficient' => $fake->randomDigitNotNull,
            'max_turbing_outflow' => $fake->randomDigitNotNull,
            'modification' => $fake->word,
            'forced_unavailability' => $fake->randomDigitNotNull,
            'forced_unavailability' => $fake->randomDigitNotNull,
            'max_storage' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $hidroExpnFields);
    }
}
