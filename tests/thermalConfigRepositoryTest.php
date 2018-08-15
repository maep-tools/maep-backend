<?php

use App\Models\thermalConfig;
use App\Repositories\thermalConfigRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class thermalConfigRepositoryTest extends TestCase
{
    use MakethermalConfigTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var thermalConfigRepository
     */
    protected $thermalConfigRepo;

    public function setUp()
    {
        parent::setUp();
        $this->thermalConfigRepo = App::make(thermalConfigRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatethermalConfig()
    {
        $thermalConfig = $this->fakethermalConfigData();
        $createdthermalConfig = $this->thermalConfigRepo->create($thermalConfig);
        $createdthermalConfig = $createdthermalConfig->toArray();
        $this->assertArrayHasKey('id', $createdthermalConfig);
        $this->assertNotNull($createdthermalConfig['id'], 'Created thermalConfig must have id specified');
        $this->assertNotNull(thermalConfig::find($createdthermalConfig['id']), 'thermalConfig with given id must be in DB');
        $this->assertModelData($thermalConfig, $createdthermalConfig);
    }

    /**
     * @test read
     */
    public function testReadthermalConfig()
    {
        $thermalConfig = $this->makethermalConfig();
        $dbthermalConfig = $this->thermalConfigRepo->find($thermalConfig->id);
        $dbthermalConfig = $dbthermalConfig->toArray();
        $this->assertModelData($thermalConfig->toArray(), $dbthermalConfig);
    }

    /**
     * @test update
     */
    public function testUpdatethermalConfig()
    {
        $thermalConfig = $this->makethermalConfig();
        $fakethermalConfig = $this->fakethermalConfigData();
        $updatedthermalConfig = $this->thermalConfigRepo->update($fakethermalConfig, $thermalConfig->id);
        $this->assertModelData($fakethermalConfig, $updatedthermalConfig->toArray());
        $dbthermalConfig = $this->thermalConfigRepo->find($thermalConfig->id);
        $this->assertModelData($fakethermalConfig, $dbthermalConfig->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletethermalConfig()
    {
        $thermalConfig = $this->makethermalConfig();
        $resp = $this->thermalConfigRepo->delete($thermalConfig->id);
        $this->assertTrue($resp);
        $this->assertNull(thermalConfig::find($thermalConfig->id), 'thermalConfig should not exist in DB');
    }
}
