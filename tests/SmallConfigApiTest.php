<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SmallConfigApiTest extends TestCase
{
    use MakeSmallConfigTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateSmallConfig()
    {
        $smallConfig = $this->fakeSmallConfigData();
        $this->json('POST', '/api/v1/smallConfigs', $smallConfig);

        $this->assertApiResponse($smallConfig);
    }

    /**
     * @test
     */
    public function testReadSmallConfig()
    {
        $smallConfig = $this->makeSmallConfig();
        $this->json('GET', '/api/v1/smallConfigs/'.$smallConfig->id);

        $this->assertApiResponse($smallConfig->toArray());
    }

    /**
     * @test
     */
    public function testUpdateSmallConfig()
    {
        $smallConfig = $this->makeSmallConfig();
        $editedSmallConfig = $this->fakeSmallConfigData();

        $this->json('PUT', '/api/v1/smallConfigs/'.$smallConfig->id, $editedSmallConfig);

        $this->assertApiResponse($editedSmallConfig);
    }

    /**
     * @test
     */
    public function testDeleteSmallConfig()
    {
        $smallConfig = $this->makeSmallConfig();
        $this->json('DELETE', '/api/v1/smallConfigs/'.$smallConfig->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/smallConfigs/'.$smallConfig->id);

        $this->assertResponseStatus(404);
    }
}
