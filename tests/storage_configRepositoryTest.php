<?php

use App\Models\storage_config;
use App\Repositories\storage_configRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class storage_configRepositoryTest extends TestCase
{
    use Makestorage_configTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var storage_configRepository
     */
    protected $storageConfigRepo;

    public function setUp()
    {
        parent::setUp();
        $this->storageConfigRepo = App::make(storage_configRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatestorage_config()
    {
        $storageConfig = $this->fakestorage_configData();
        $createdstorage_config = $this->storageConfigRepo->create($storageConfig);
        $createdstorage_config = $createdstorage_config->toArray();
        $this->assertArrayHasKey('id', $createdstorage_config);
        $this->assertNotNull($createdstorage_config['id'], 'Created storage_config must have id specified');
        $this->assertNotNull(storage_config::find($createdstorage_config['id']), 'storage_config with given id must be in DB');
        $this->assertModelData($storageConfig, $createdstorage_config);
    }

    /**
     * @test read
     */
    public function testReadstorage_config()
    {
        $storageConfig = $this->makestorage_config();
        $dbstorage_config = $this->storageConfigRepo->find($storageConfig->id);
        $dbstorage_config = $dbstorage_config->toArray();
        $this->assertModelData($storageConfig->toArray(), $dbstorage_config);
    }

    /**
     * @test update
     */
    public function testUpdatestorage_config()
    {
        $storageConfig = $this->makestorage_config();
        $fakestorage_config = $this->fakestorage_configData();
        $updatedstorage_config = $this->storageConfigRepo->update($fakestorage_config, $storageConfig->id);
        $this->assertModelData($fakestorage_config, $updatedstorage_config->toArray());
        $dbstorage_config = $this->storageConfigRepo->find($storageConfig->id);
        $this->assertModelData($fakestorage_config, $dbstorage_config->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletestorage_config()
    {
        $storageConfig = $this->makestorage_config();
        $resp = $this->storageConfigRepo->delete($storageConfig->id);
        $this->assertTrue($resp);
        $this->assertNull(storage_config::find($storageConfig->id), 'storage_config should not exist in DB');
    }
}
