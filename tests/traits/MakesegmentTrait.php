<?php

use Faker\Factory as Faker;
use App\Models\segment;
use App\Repositories\segmentRepository;

trait MakesegmentTrait
{
    /**
     * Create fake instance of segment and save it in database
     *
     * @param array $segmentFields
     * @return segment
     */
    public function makesegment($segmentFields = [])
    {
        /** @var segmentRepository $segmentRepo */
        $segmentRepo = App::make(segmentRepository::class);
        $theme = $this->fakesegmentData($segmentFields);
        return $segmentRepo->create($theme);
    }

    /**
     * Get fake instance of segment
     *
     * @param array $segmentFields
     * @return segment
     */
    public function fakesegment($segmentFields = [])
    {
        return new segment($this->fakesegmentData($segmentFields));
    }

    /**
     * Get fake data of segment
     *
     * @param array $postFields
     * @return array
     */
    public function fakesegmentData($segmentFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'process_id' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $segmentFields);
    }
}
