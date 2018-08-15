<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class storage_configApiTest extends TestCase
{
    use Makestorage_configTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatestorage_config()
    {
        $storageConfig = $this->fakestorage_configData();
        $this->json('POST', '/api/v1/storageConfigs', $storageConfig);

        $this->assertApiResponse($storageConfig);
    }

    /**
     * @test
     */
    public function testReadstorage_config()
    {
        $storageConfig = $this->makestorage_config();
        $this->json('GET', '/api/v1/storageConfigs/'.$storageConfig->id);

        $this->assertApiResponse($storageConfig->toArray());
    }

    /**
     * @test
     */
    public function testUpdatestorage_config()
    {
        $storageConfig = $this->makestorage_config();
        $editedstorage_config = $this->fakestorage_configData();

        $this->json('PUT', '/api/v1/storageConfigs/'.$storageConfig->id, $editedstorage_config);

        $this->assertApiResponse($editedstorage_config);
    }

    /**
     * @test
     */
    public function testDeletestorage_config()
    {
        $storageConfig = $this->makestorage_config();
        $this->json('DELETE', '/api/v1/storageConfigs/'.$storageConfig->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/storageConfigs/'.$storageConfig->id);

        $this->assertResponseStatus(404);
    }
}
