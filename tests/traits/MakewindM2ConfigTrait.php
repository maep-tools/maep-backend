<?php

use Faker\Factory as Faker;
use App\Models\WindM2Config;
use App\Repositories\WindM2ConfigRepository;

trait MakeWindM2ConfigTrait
{
    /**
     * Create fake instance of WindM2Config and save it in database
     *
     * @param array $windM2ConfigFields
     * @return WindM2Config
     */
    public function makeWindM2Config($windM2ConfigFields = [])
    {
        /** @var WindM2ConfigRepository $windM2ConfigRepo */
        $windM2ConfigRepo = App::make(WindM2ConfigRepository::class);
        $theme = $this->fakeWindM2ConfigData($windM2ConfigFields);
        return $windM2ConfigRepo->create($theme);
    }

    /**
     * Get fake instance of WindM2Config
     *
     * @param array $windM2ConfigFields
     * @return WindM2Config
     */
    public function fakeWindM2Config($windM2ConfigFields = [])
    {
        return new WindM2Config($this->fakeWindM2ConfigData($windM2ConfigFields));
    }

    /**
     * Get fake data of WindM2Config
     *
     * @param array $postFields
     * @return array
     */
    public function fakeWindM2ConfigData($windM2ConfigFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'rows' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $windM2ConfigFields);
    }
}
