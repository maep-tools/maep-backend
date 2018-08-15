<?php

use Faker\Factory as Faker;
use App\Models\categories;
use App\Repositories\categoriesRepository;

trait MakecategoriesTrait
{
    /**
     * Create fake instance of categories and save it in database
     *
     * @param array $categoriesFields
     * @return categories
     */
    public function makecategories($categoriesFields = [])
    {
        /** @var categoriesRepository $categoriesRepo */
        $categoriesRepo = App::make(categoriesRepository::class);
        $theme = $this->fakecategoriesData($categoriesFields);
        return $categoriesRepo->create($theme);
    }

    /**
     * Get fake instance of categories
     *
     * @param array $categoriesFields
     * @return categories
     */
    public function fakecategories($categoriesFields = [])
    {
        return new categories($this->fakecategoriesData($categoriesFields));
    }

    /**
     * Get fake data of categories
     *
     * @param array $postFields
     * @return array
     */
    public function fakecategoriesData($categoriesFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'parentId' => $fake->randomDigitNotNull,
            'component' => $fake->word,
            'disabled' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $categoriesFields);
    }
}
