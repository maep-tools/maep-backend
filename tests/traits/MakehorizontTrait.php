<?php

use Faker\Factory as Faker;
use App\Models\horizont;
use App\Repositories\horizontRepository;

trait MakehorizontTrait
{
    /**
     * Create fake instance of horizont and save it in database
     *
     * @param array $horizontFields
     * @return horizont
     */
    public function makehorizont($horizontFields = [])
    {
        /** @var horizontRepository $horizontRepo */
        $horizontRepo = App::make(horizontRepository::class);
        $theme = $this->fakehorizontData($horizontFields);
        return $horizontRepo->create($theme);
    }

    /**
     * Get fake instance of horizont
     *
     * @param array $horizontFields
     * @return horizont
     */
    public function fakehorizont($horizontFields = [])
    {
        return new horizont($this->fakehorizontData($horizontFields));
    }

    /**
     * Get fake data of horizont
     *
     * @param array $postFields
     * @return array
     */
    public function fakehorizontData($horizontFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'process_id' => $fake->randomDigitNotNull,
            'period' => $fake->word,
            'national' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $horizontFields);
    }
}
