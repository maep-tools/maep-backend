<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HidroConfigApiTest extends TestCase
{
    use MakeHidroConfigTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateHidroConfig()
    {
        $hidroConfig = $this->fakeHidroConfigData();
        $this->json('POST', '/api/v1/hidroConfigs', $hidroConfig);

        $this->assertApiResponse($hidroConfig);
    }

    /**
     * @test
     */
    public function testReadHidroConfig()
    {
        $hidroConfig = $this->makeHidroConfig();
        $this->json('GET', '/api/v1/hidroConfigs/'.$hidroConfig->id);

        $this->assertApiResponse($hidroConfig->toArray());
    }

    /**
     * @test
     */
    public function testUpdateHidroConfig()
    {
        $hidroConfig = $this->makeHidroConfig();
        $editedHidroConfig = $this->fakeHidroConfigData();

        $this->json('PUT', '/api/v1/hidroConfigs/'.$hidroConfig->id, $editedHidroConfig);

        $this->assertApiResponse($editedHidroConfig);
    }

    /**
     * @test
     */
    public function testDeleteHidroConfig()
    {
        $hidroConfig = $this->makeHidroConfig();
        $this->json('DELETE', '/api/v1/hidroConfigs/'.$hidroConfig->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/hidroConfigs/'.$hidroConfig->id);

        $this->assertResponseStatus(404);
    }
}
