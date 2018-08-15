<?php

use Faker\Factory as Faker;
use App\Models\EntranceStages;
use App\Repositories\EntranceStagesRepository;

trait MakeEntranceStagesTrait
{
    /**
     * Create fake instance of EntranceStages and save it in database
     *
     * @param array $entranceStagesFields
     * @return EntranceStages
     */
    public function makeEntranceStages($entranceStagesFields = [])
    {
        /** @var EntranceStagesRepository $entranceStagesRepo */
        $entranceStagesRepo = App::make(EntranceStagesRepository::class);
        $theme = $this->fakeEntranceStagesData($entranceStagesFields);
        return $entranceStagesRepo->create($theme);
    }

    /**
     * Get fake instance of EntranceStages
     *
     * @param array $entranceStagesFields
     * @return EntranceStages
     */
    public function fakeEntranceStages($entranceStagesFields = [])
    {
        return new EntranceStages($this->fakeEntranceStagesData($entranceStagesFields));
    }

    /**
     * Get fake data of EntranceStages
     *
     * @param array $postFields
     * @return array
     */
    public function fakeEntranceStagesData($entranceStagesFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $entranceStagesFields);
    }
}
