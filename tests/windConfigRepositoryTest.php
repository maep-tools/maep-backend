<?php

use App\Models\windConfig;
use App\Repositories\windConfigRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class windConfigRepositoryTest extends TestCase
{
    use MakewindConfigTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var windConfigRepository
     */
    protected $windConfigRepo;

    public function setUp()
    {
        parent::setUp();
        $this->windConfigRepo = App::make(windConfigRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatewindConfig()
    {
        $windConfig = $this->fakewindConfigData();
        $createdwindConfig = $this->windConfigRepo->create($windConfig);
        $createdwindConfig = $createdwindConfig->toArray();
        $this->assertArrayHasKey('id', $createdwindConfig);
        $this->assertNotNull($createdwindConfig['id'], 'Created windConfig must have id specified');
        $this->assertNotNull(windConfig::find($createdwindConfig['id']), 'windConfig with given id must be in DB');
        $this->assertModelData($windConfig, $createdwindConfig);
    }

    /**
     * @test read
     */
    public function testReadwindConfig()
    {
        $windConfig = $this->makewindConfig();
        $dbwindConfig = $this->windConfigRepo->find($windConfig->id);
        $dbwindConfig = $dbwindConfig->toArray();
        $this->assertModelData($windConfig->toArray(), $dbwindConfig);
    }

    /**
     * @test update
     */
    public function testUpdatewindConfig()
    {
        $windConfig = $this->makewindConfig();
        $fakewindConfig = $this->fakewindConfigData();
        $updatedwindConfig = $this->windConfigRepo->update($fakewindConfig, $windConfig->id);
        $this->assertModelData($fakewindConfig, $updatedwindConfig->toArray());
        $dbwindConfig = $this->windConfigRepo->find($windConfig->id);
        $this->assertModelData($fakewindConfig, $dbwindConfig->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletewindConfig()
    {
        $windConfig = $this->makewindConfig();
        $resp = $this->windConfigRepo->delete($windConfig->id);
        $this->assertTrue($resp);
        $this->assertNull(windConfig::find($windConfig->id), 'windConfig should not exist in DB');
    }
}
