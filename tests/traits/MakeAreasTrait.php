<?php

use Faker\Factory as Faker;
use App\Models\Areas;
use App\Repositories\AreasRepository;

trait MakeAreasTrait
{
    /**
     * Create fake instance of Areas and save it in database
     *
     * @param array $areasFields
     * @return Areas
     */
    public function makeAreas($areasFields = [])
    {
        /** @var AreasRepository $areasRepo */
        $areasRepo = App::make(AreasRepository::class);
        $theme = $this->fakeAreasData($areasFields);
        return $areasRepo->create($theme);
    }

    /**
     * Get fake instance of Areas
     *
     * @param array $areasFields
     * @return Areas
     */
    public function fakeAreas($areasFields = [])
    {
        return new Areas($this->fakeAreasData($areasFields));
    }

    /**
     * Get fake data of Areas
     *
     * @param array $postFields
     * @return array
     */
    public function fakeAreasData($areasFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'process_id' => $fake->randomDigitNotNull,
            'name' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $areasFields);
    }
}
