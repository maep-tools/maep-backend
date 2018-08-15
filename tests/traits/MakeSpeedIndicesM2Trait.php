<?php

use Faker\Factory as Faker;
use App\Models\SpeedIndicesM2;
use App\Repositories\SpeedIndicesM2Repository;

trait MakeSpeedIndicesM2Trait
{
    /**
     * Create fake instance of SpeedIndicesM2 and save it in database
     *
     * @param array $speedIndicesM2Fields
     * @return SpeedIndicesM2
     */
    public function makeSpeedIndicesM2($speedIndicesM2Fields = [])
    {
        /** @var SpeedIndicesM2Repository $speedIndicesM2Repo */
        $speedIndicesM2Repo = App::make(SpeedIndicesM2Repository::class);
        $theme = $this->fakeSpeedIndicesM2Data($speedIndicesM2Fields);
        return $speedIndicesM2Repo->create($theme);
    }

    /**
     * Get fake instance of SpeedIndicesM2
     *
     * @param array $speedIndicesM2Fields
     * @return SpeedIndicesM2
     */
    public function fakeSpeedIndicesM2($speedIndicesM2Fields = [])
    {
        return new SpeedIndicesM2($this->fakeSpeedIndicesM2Data($speedIndicesM2Fields));
    }

    /**
     * Get fake data of SpeedIndicesM2
     *
     * @param array $postFields
     * @return array
     */
    public function fakeSpeedIndicesM2Data($speedIndicesM2Fields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'lol' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $speedIndicesM2Fields);
    }
}
