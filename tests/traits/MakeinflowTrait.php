<?php

use Faker\Factory as Faker;
use App\Models\inflow;
use App\Repositories\inflowRepository;

trait MakeinflowTrait
{
    /**
     * Create fake instance of inflow and save it in database
     *
     * @param array $inflowFields
     * @return inflow
     */
    public function makeinflow($inflowFields = [])
    {
        /** @var inflowRepository $inflowRepo */
        $inflowRepo = App::make(inflowRepository::class);
        $theme = $this->fakeinflowData($inflowFields);
        return $inflowRepo->create($theme);
    }

    /**
     * Get fake instance of inflow
     *
     * @param array $inflowFields
     * @return inflow
     */
    public function fakeinflow($inflowFields = [])
    {
        return new inflow($this->fakeinflowData($inflowFields));
    }

    /**
     * Get fake data of inflow
     *
     * @param array $postFields
     * @return array
     */
    public function fakeinflowData($inflowFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'scenario' => $fake->randomDigitNotNull,
            'horizont_id' => $fake->randomDigitNotNull,
            'hidro_config_id' => $fake->randomDigitNotNull,
            'value' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $inflowFields);
    }
}
