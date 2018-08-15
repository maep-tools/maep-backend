<?php

use Faker\Factory as Faker;
use App\Models\demand;
use App\Repositories\demandRepository;

trait MakedemandTrait
{
    /**
     * Create fake instance of demand and save it in database
     *
     * @param array $demandFields
     * @return demand
     */
    public function makedemand($demandFields = [])
    {
        /** @var demandRepository $demandRepo */
        $demandRepo = App::make(demandRepository::class);
        $theme = $this->fakedemandData($demandFields);
        return $demandRepo->create($theme);
    }

    /**
     * Get fake instance of demand
     *
     * @param array $demandFields
     * @return demand
     */
    public function fakedemand($demandFields = [])
    {
        return new demand($this->fakedemandData($demandFields));
    }

    /**
     * Get fake data of demand
     *
     * @param array $postFields
     * @return array
     */
    public function fakedemandData($demandFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'horizont_id' => $fake->randomDigitNotNull,
            'area_id' => $fake->randomDigitNotNull,
            'factor' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $demandFields);
    }
}
