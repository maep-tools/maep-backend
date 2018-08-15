<?php

use Faker\Factory as Faker;
use App\Models\inflowWindM2;
use App\Repositories\inflowWindM2Repository;

trait MakeinflowWindM2Trait
{
    /**
     * Create fake instance of inflowWindM2 and save it in database
     *
     * @param array $inflowWindM2Fields
     * @return inflowWindM2
     */
    public function makeinflowWindM2($inflowWindM2Fields = [])
    {
        /** @var inflowWindM2Repository $inflowWindM2Repo */
        $inflowWindM2Repo = App::make(inflowWindM2Repository::class);
        $theme = $this->fakeinflowWindM2Data($inflowWindM2Fields);
        return $inflowWindM2Repo->create($theme);
    }

    /**
     * Get fake instance of inflowWindM2
     *
     * @param array $inflowWindM2Fields
     * @return inflowWindM2
     */
    public function fakeinflowWindM2($inflowWindM2Fields = [])
    {
        return new inflowWindM2($this->fakeinflowWindM2Data($inflowWindM2Fields));
    }

    /**
     * Get fake data of inflowWindM2
     *
     * @param array $postFields
     * @return array
     */
    public function fakeinflowWindM2Data($inflowWindM2Fields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'fat' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $inflowWindM2Fields);
    }
}
