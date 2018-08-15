<?php

use Faker\Factory as Faker;
use App\Models\WPowCurveM2;
use App\Repositories\WPowCurveM2Repository;

trait MakeWPowCurveM2Trait
{
    /**
     * Create fake instance of WPowCurveM2 and save it in database
     *
     * @param array $wPowCurveM2Fields
     * @return WPowCurveM2
     */
    public function makeWPowCurveM2($wPowCurveM2Fields = [])
    {
        /** @var WPowCurveM2Repository $wPowCurveM2Repo */
        $wPowCurveM2Repo = App::make(WPowCurveM2Repository::class);
        $theme = $this->fakeWPowCurveM2Data($wPowCurveM2Fields);
        return $wPowCurveM2Repo->create($theme);
    }

    /**
     * Get fake instance of WPowCurveM2
     *
     * @param array $wPowCurveM2Fields
     * @return WPowCurveM2
     */
    public function fakeWPowCurveM2($wPowCurveM2Fields = [])
    {
        return new WPowCurveM2($this->fakeWPowCurveM2Data($wPowCurveM2Fields));
    }

    /**
     * Get fake data of WPowCurveM2
     *
     * @param array $postFields
     * @return array
     */
    public function fakeWPowCurveM2Data($wPowCurveM2Fields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'p' => $fake->randomDigitNotNull,
            'CT' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $wPowCurveM2Fields);
    }
}
