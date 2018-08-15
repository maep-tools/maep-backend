<?php

use Faker\Factory as Faker;
use App\Models\lines;
use App\Repositories\linesRepository;

trait MakelinesTrait
{
    /**
     * Create fake instance of lines and save it in database
     *
     * @param array $linesFields
     * @return lines
     */
    public function makelines($linesFields = [])
    {
        /** @var linesRepository $linesRepo */
        $linesRepo = App::make(linesRepository::class);
        $theme = $this->fakelinesData($linesFields);
        return $linesRepo->create($theme);
    }

    /**
     * Get fake instance of lines
     *
     * @param array $linesFields
     * @return lines
     */
    public function fakelines($linesFields = [])
    {
        return new lines($this->fakelinesData($linesFields));
    }

    /**
     * Get fake data of lines
     *
     * @param array $postFields
     * @return array
     */
    public function fakelinesData($linesFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'a_initial' => $fake->randomDigitNotNull,
            'b_final' => $fake->randomDigitNotNull,
            'a_to_b' => $fake->randomDigitNotNull,
            'b_to_a' => $fake->randomDigitNotNull,
            'efficiency' => $fake->randomDigitNotNull,
            'resistence' => $fake->randomDigitNotNull,
            'reactance' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $linesFields);
    }
}
