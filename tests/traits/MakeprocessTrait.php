<?php

use Faker\Factory as Faker;
use App\Models\process;
use App\Repositories\processRepository;

trait MakeprocessTrait
{
    /**
     * Create fake instance of process and save it in database
     *
     * @param array $processFields
     * @return process
     */
    public function makeprocess($processFields = [])
    {
        /** @var processRepository $processRepo */
        $processRepo = App::make(processRepository::class);
        $theme = $this->fakeprocessData($processFields);
        return $processRepo->create($theme);
    }

    /**
     * Get fake instance of process
     *
     * @param array $processFields
     * @return process
     */
    public function fakeprocess($processFields = [])
    {
        return new process($this->fakeprocessData($processFields));
    }

    /**
     * Get fake data of process
     *
     * @param array $postFields
     * @return array
     */
    public function fakeprocessData($processFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'statusId' => $fake->randomDigitNotNull,
            'templateId' => $fake->randomDigitNotNull,
            'max_iter' => $fake->randomDigitNotNull,
            'extra_stages' => $fake->randomDigitNotNull,
            'stages' => $fake->randomDigitNotNull,
            'seriesBack' => $fake->randomDigitNotNull,
            'seriesForw' => $fake->randomDigitNotNull,
            'stochastic' => $fake->randomDigitNotNull,
            'variance' => $fake->randomDigitNotNull,
            'sensDem' => $fake->randomDigitNotNull,
            'speed_out' => $fake->randomDigitNotNull,
            'speed_in' => $fake->randomDigitNotNull,
            'eps_area' => $fake->randomDigitNotNull,
            'eps_all' => $fake->randomDigitNotNull,
            'eps_risk' => $fake->randomDigitNotNull,
            'commit' => $fake->randomDigitNotNull,
            'lag_max' => $fake->randomDigitNotNull,
            'testing_t' => $fake->randomDigitNotNull,
            'd_correl' => $fake->randomDigitNotNull,
            'seasonality' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $processFields);
    }
}
