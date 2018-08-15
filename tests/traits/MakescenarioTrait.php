<?php

use Faker\Factory as Faker;
use App\Models\scenario;
use App\Repositories\scenarioRepository;

trait MakescenarioTrait
{
    /**
     * Create fake instance of scenario and save it in database
     *
     * @param array $scenarioFields
     * @return scenario
     */
    public function makescenario($scenarioFields = [])
    {
        /** @var scenarioRepository $scenarioRepo */
        $scenarioRepo = App::make(scenarioRepository::class);
        $theme = $this->fakescenarioData($scenarioFields);
        return $scenarioRepo->create($theme);
    }

    /**
     * Get fake instance of scenario
     *
     * @param array $scenarioFields
     * @return scenario
     */
    public function fakescenario($scenarioFields = [])
    {
        return new scenario($this->fakescenarioData($scenarioFields));
    }

    /**
     * Get fake data of scenario
     *
     * @param array $postFields
     * @return array
     */
    public function fakescenarioData($scenarioFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $scenarioFields);
    }
}
