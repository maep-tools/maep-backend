<?php

use App\Models\SmallConfig;
use App\Repositories\SmallConfigRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SmallConfigRepositoryTest extends TestCase
{
    use MakeSmallConfigTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var SmallConfigRepository
     */
    protected $smallConfigRepo;

    public function setUp()
    {
        parent::setUp();
        $this->smallConfigRepo = App::make(SmallConfigRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateSmallConfig()
    {
        $smallConfig = $this->fakeSmallConfigData();
        $createdSmallConfig = $this->smallConfigRepo->create($smallConfig);
        $createdSmallConfig = $createdSmallConfig->toArray();
        $this->assertArrayHasKey('id', $createdSmallConfig);
        $this->assertNotNull($createdSmallConfig['id'], 'Created SmallConfig must have id specified');
        $this->assertNotNull(SmallConfig::find($createdSmallConfig['id']), 'SmallConfig with given id must be in DB');
        $this->assertModelData($smallConfig, $createdSmallConfig);
    }

    /**
     * @test read
     */
    public function testReadSmallConfig()
    {
        $smallConfig = $this->makeSmallConfig();
        $dbSmallConfig = $this->smallConfigRepo->find($smallConfig->id);
        $dbSmallConfig = $dbSmallConfig->toArray();
        $this->assertModelData($smallConfig->toArray(), $dbSmallConfig);
    }

    /**
     * @test update
     */
    public function testUpdateSmallConfig()
    {
        $smallConfig = $this->makeSmallConfig();
        $fakeSmallConfig = $this->fakeSmallConfigData();
        $updatedSmallConfig = $this->smallConfigRepo->update($fakeSmallConfig, $smallConfig->id);
        $this->assertModelData($fakeSmallConfig, $updatedSmallConfig->toArray());
        $dbSmallConfig = $this->smallConfigRepo->find($smallConfig->id);
        $this->assertModelData($fakeSmallConfig, $dbSmallConfig->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteSmallConfig()
    {
        $smallConfig = $this->makeSmallConfig();
        $resp = $this->smallConfigRepo->delete($smallConfig->id);
        $this->assertTrue($resp);
        $this->assertNull(SmallConfig::find($smallConfig->id), 'SmallConfig should not exist in DB');
    }
}
