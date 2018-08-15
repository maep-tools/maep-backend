<?php

use App\Models\Blocks;
use App\Repositories\BlocksRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BlocksRepositoryTest extends TestCase
{
    use MakeBlocksTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var BlocksRepository
     */
    protected $blocksRepo;

    public function setUp()
    {
        parent::setUp();
        $this->blocksRepo = App::make(BlocksRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateBlocks()
    {
        $blocks = $this->fakeBlocksData();
        $createdBlocks = $this->blocksRepo->create($blocks);
        $createdBlocks = $createdBlocks->toArray();
        $this->assertArrayHasKey('id', $createdBlocks);
        $this->assertNotNull($createdBlocks['id'], 'Created Blocks must have id specified');
        $this->assertNotNull(Blocks::find($createdBlocks['id']), 'Blocks with given id must be in DB');
        $this->assertModelData($blocks, $createdBlocks);
    }

    /**
     * @test read
     */
    public function testReadBlocks()
    {
        $blocks = $this->makeBlocks();
        $dbBlocks = $this->blocksRepo->find($blocks->id);
        $dbBlocks = $dbBlocks->toArray();
        $this->assertModelData($blocks->toArray(), $dbBlocks);
    }

    /**
     * @test update
     */
    public function testUpdateBlocks()
    {
        $blocks = $this->makeBlocks();
        $fakeBlocks = $this->fakeBlocksData();
        $updatedBlocks = $this->blocksRepo->update($fakeBlocks, $blocks->id);
        $this->assertModelData($fakeBlocks, $updatedBlocks->toArray());
        $dbBlocks = $this->blocksRepo->find($blocks->id);
        $this->assertModelData($fakeBlocks, $dbBlocks->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteBlocks()
    {
        $blocks = $this->makeBlocks();
        $resp = $this->blocksRepo->delete($blocks->id);
        $this->assertTrue($resp);
        $this->assertNull(Blocks::find($blocks->id), 'Blocks should not exist in DB');
    }
}
