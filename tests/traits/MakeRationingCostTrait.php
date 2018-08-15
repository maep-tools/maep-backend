<?php

use Faker\Factory as Faker;
use App\Models\RationingCost;
use App\Repositories\RationingCostRepository;

trait MakeRationingCostTrait
{
    /**
     * Create fake instance of RationingCost and save it in database
     *
     * @param array $rationingCostFields
     * @return RationingCost
     */
    public function makeRationingCost($rationingCostFields = [])
    {
        /** @var RationingCostRepository $rationingCostRepo */
        $rationingCostRepo = App::make(RationingCostRepository::class);
        $theme = $this->fakeRationingCostData($rationingCostFields);
        return $rationingCostRepo->create($theme);
    }

    /**
     * Get fake instance of RationingCost
     *
     * @param array $rationingCostFields
     * @return RationingCost
     */
    public function fakeRationingCost($rationingCostFields = [])
    {
        return new RationingCost($this->fakeRationingCostData($rationingCostFields));
    }

    /**
     * Get fake data of RationingCost
     *
     * @param array $postFields
     * @return array
     */
    public function fakeRationingCostData($rationingCostFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'horizont' => $fake->word,
            'segment1' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $rationingCostFields);
    }
}
