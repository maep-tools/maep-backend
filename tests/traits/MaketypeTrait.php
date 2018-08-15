<?php

use Faker\Factory as Faker;
use App\Models\type;
use App\Repositories\typeRepository;

trait MaketypeTrait
{
    /**
     * Create fake instance of type and save it in database
     *
     * @param array $typeFields
     * @return type
     */
    public function maketype($typeFields = [])
    {
        /** @var typeRepository $typeRepo */
        $typeRepo = App::make(typeRepository::class);
        $theme = $this->faketypeData($typeFields);
        return $typeRepo->create($theme);
    }

    /**
     * Get fake instance of type
     *
     * @param array $typeFields
     * @return type
     */
    public function faketype($typeFields = [])
    {
        return new type($this->faketypeData($typeFields));
    }

    /**
     * Get fake data of type
     *
     * @param array $postFields
     * @return array
     */
    public function faketypeData($typeFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $typeFields);
    }
}
