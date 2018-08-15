<?php

use App\Models\HidroConfig;
use App\Repositories\HidroConfigRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HidroConfigRepositoryTest extends TestCase
{
    use MakeHidroConfigTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var HidroConfigRepository
     */
    protected $hidroConfigRepo;

    public function setUp()
    {
        parent::setUp();
        $this->hidroConfigRepo = App::make(HidroConfigRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateHidroConfig()
    {
        $hidroConfig = $this->fakeHidroConfigData();
        $createdHidroConfig = $this->hidroConfigRepo->create($hidroConfig);
        $createdHidroConfig = $createdHidroConfig->toArray();
        $this->assertArrayHasKey('id', $createdHidroConfig);
        $this->assertNotNull($createdHidroConfig['id'], 'Created HidroConfig must have id specified');
        $this->assertNotNull(HidroConfig::find($createdHidroConfig['id']), 'HidroConfig with given id must be in DB');
        $this->assertModelData($hidroConfig, $createdHidroConfig);
    }

    /**
     * @test read
     */
    public function testReadHidroConfig()
    {
        $hidroConfig = $this->makeHidroConfig();
        $dbHidroConfig = $this->hidroConfigRepo->find($hidroConfig->id);
        $dbHidroConfig = $dbHidroConfig->toArray();
        $this->assertModelData($hidroConfig->toArray(), $dbHidroConfig);
    }

    /**
     * @test update
     */
    public function testUpdateHidroConfig()
    {
        $hidroConfig = $this->makeHidroConfig();
        $fakeHidroConfig = $this->fakeHidroConfigData();
        $updatedHidroConfig = $this->hidroConfigRepo->update($fakeHidroConfig, $hidroConfig->id);
        $this->assertModelData($fakeHidroConfig, $updatedHidroConfig->toArray());
        $dbHidroConfig = $this->hidroConfigRepo->find($hidroConfig->id);
        $this->assertModelData($fakeHidroConfig, $dbHidroConfig->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteHidroConfig()
    {
        $hidroConfig = $this->makeHidroConfig();
        $resp = $this->hidroConfigRepo->delete($hidroConfig->id);
        $this->assertTrue($resp);
        $this->assertNull(HidroConfig::find($hidroConfig->id), 'HidroConfig should not exist in DB');
    }
}
