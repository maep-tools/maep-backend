<?php

use Faker\Factory as Faker;
use App\Models\linesExpansion;
use App\Repositories\linesExpansionRepository;

trait MakelinesExpansionTrait
{
    /**
     * Create fake instance of linesExpansion and save it in database
     *
     * @param array $linesExpansionFields
     * @return linesExpansion
     */
    public function makelinesExpansion($linesExpansionFields = [])
    {
        /** @var linesExpansionRepository $linesExpansionRepo */
        $linesExpansionRepo = App::make(linesExpansionRepository::class);
        $theme = $this->fakelinesExpansionData($linesExpansionFields);
        return $linesExpansionRepo->create($theme);
    }

    /**
     * Get fake instance of linesExpansion
     *
     * @param array $linesExpansionFields
     * @return linesExpansion
     */
    public function fakelinesExpansion($linesExpansionFields = [])
    {
        return new linesExpansion($this->fakelinesExpansionData($linesExpansionFields));
    }

    /**
     * Get fake data of linesExpansion
     *
     * @param array $postFields
     * @return array
     */
    public function fakelinesExpansionData($linesExpansionFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'a_initial' => $fake->randomDigitNotNull,
            'a_final' => $fake->randomDigitNotNull,
            'stage' => $fake->randomDigitNotNull,
            'a_b' => $fake->randomDigitNotNull,
            'b_ai' => $fake->randomDigitNotNull,
            'efficiency' => $fake->randomDigitNotNull,
            'resistence' => $fake->randomDigitNotNull,
            'reactance' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $linesExpansionFields);
    }
}
