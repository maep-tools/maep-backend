<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class thermalConfigApiTest extends TestCase
{
    use MakethermalConfigTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatethermalConfig()
    {
        $thermalConfig = $this->fakethermalConfigData();
        $this->json('POST', '/api/v1/thermalConfigs', $thermalConfig);

        $this->assertApiResponse($thermalConfig);
    }

    /**
     * @test
     */
    public function testReadthermalConfig()
    {
        $thermalConfig = $this->makethermalConfig();
        $this->json('GET', '/api/v1/thermalConfigs/'.$thermalConfig->id);

        $this->assertApiResponse($thermalConfig->toArray());
    }

    /**
     * @test
     */
    public function testUpdatethermalConfig()
    {
        $thermalConfig = $this->makethermalConfig();
        $editedthermalConfig = $this->fakethermalConfigData();

        $this->json('PUT', '/api/v1/thermalConfigs/'.$thermalConfig->id, $editedthermalConfig);

        $this->assertApiResponse($editedthermalConfig);
    }

    /**
     * @test
     */
    public function testDeletethermalConfig()
    {
        $thermalConfig = $this->makethermalConfig();
        $this->json('DELETE', '/api/v1/thermalConfigs/'.$thermalConfig->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/thermalConfigs/'.$thermalConfig->id);

        $this->assertResponseStatus(404);
    }
}
