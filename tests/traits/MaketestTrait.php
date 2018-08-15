<?php

use Faker\Factory as Faker;
use App\Models\test;
use App\Repositories\testRepository;

trait MaketestTrait
{
    /**
     * Create fake instance of test and save it in database
     *
     * @param array $testFields
     * @return test
     */
    public function maketest($testFields = [])
    {
        /** @var testRepository $testRepo */
        $testRepo = App::make(testRepository::class);
        $theme = $this->faketestData($testFields);
        return $testRepo->create($theme);
    }

    /**
     * Get fake instance of test
     *
     * @param array $testFields
     * @return test
     */
    public function faketest($testFields = [])
    {
        return new test($this->faketestData($testFields));
    }

    /**
     * Get fake data of test
     *
     * @param array $postFields
     * @return array
     */
    public function faketestData($testFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'title' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $testFields);
    }
}
