<?php

use Faker\Factory as Faker;
use App\Models\month;
use App\Repositories\monthRepository;

trait MakemonthTrait
{
    /**
     * Create fake instance of month and save it in database
     *
     * @param array $monthFields
     * @return month
     */
    public function makemonth($monthFields = [])
    {
        /** @var monthRepository $monthRepo */
        $monthRepo = App::make(monthRepository::class);
        $theme = $this->fakemonthData($monthFields);
        return $monthRepo->create($theme);
    }

    /**
     * Get fake instance of month
     *
     * @param array $monthFields
     * @return month
     */
    public function fakemonth($monthFields = [])
    {
        return new month($this->fakemonthData($monthFields));
    }

    /**
     * Get fake data of month
     *
     * @param array $postFields
     * @return array
     */
    public function fakemonthData($monthFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'value' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $monthFields);
    }
}
