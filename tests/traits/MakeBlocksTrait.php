<?php

use Faker\Factory as Faker;
use App\Models\Blocks;
use App\Repositories\BlocksRepository;

trait MakeBlocksTrait
{
    /**
     * Create fake instance of Blocks and save it in database
     *
     * @param array $blocksFields
     * @return Blocks
     */
    public function makeBlocks($blocksFields = [])
    {
        /** @var BlocksRepository $blocksRepo */
        $blocksRepo = App::make(BlocksRepository::class);
        $theme = $this->fakeBlocksData($blocksFields);
        return $blocksRepo->create($theme);
    }

    /**
     * Get fake instance of Blocks
     *
     * @param array $blocksFields
     * @return Blocks
     */
    public function fakeBlocks($blocksFields = [])
    {
        return new Blocks($this->fakeBlocksData($blocksFields));
    }

    /**
     * Get fake data of Blocks
     *
     * @param array $postFields
     * @return array
     */
    public function fakeBlocksData($blocksFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'duration' => $fake->randomDigitNotNull,
            'participation' => $fake->randomDigitNotNull,
            'storage_restrictions' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $blocksFields);
    }
}
