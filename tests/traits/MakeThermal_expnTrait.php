<?php

use Faker\Factory as Faker;
use App\Models\Thermal_expn;
use App\Repositories\Thermal_expnRepository;

trait MakeThermal_expnTrait
{
    /**
     * Create fake instance of Thermal_expn and save it in database
     *
     * @param array $thermalExpnFields
     * @return Thermal_expn
     */
    public function makeThermal_expn($thermalExpnFields = [])
    {
        /** @var Thermal_expnRepository $thermalExpnRepo */
        $thermalExpnRepo = App::make(Thermal_expnRepository::class);
        $theme = $this->fakeThermal_expnData($thermalExpnFields);
        return $thermalExpnRepo->create($theme);
    }

    /**
     * Get fake instance of Thermal_expn
     *
     * @param array $thermalExpnFields
     * @return Thermal_expn
     */
    public function fakeThermal_expn($thermalExpnFields = [])
    {
        return new Thermal_expn($this->fakeThermal_expnData($thermalExpnFields));
    }

    /**
     * Get fake data of Thermal_expn
     *
     * @param array $postFields
     * @return array
     */
    public function fakeThermal_expnData($thermalExpnFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'thermal_config_id' => $fake->randomDigitNotNull,
            'modification' => $fake->word,
            'max' => $fake->randomDigitNotNull,
            'gen_min' => $fake->randomDigitNotNull,
            'gen_max' => $fake->randomDigitNotNull,
            'forced_unavailability' => $fake->randomDigitNotNull,
            'historic_unavailability' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $thermalExpnFields);
    }
}
