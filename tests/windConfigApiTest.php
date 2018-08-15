<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class windConfigApiTest extends TestCase
{
    use MakewindConfigTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatewindConfig()
    {
        $windConfig = $this->fakewindConfigData();
        $this->json('POST', '/api/v1/windConfigs', $windConfig);

        $this->assertApiResponse($windConfig);
    }

    /**
     * @test
     */
    public function testReadwindConfig()
    {
        $windConfig = $this->makewindConfig();
        $this->json('GET', '/api/v1/windConfigs/'.$windConfig->id);

        $this->assertApiResponse($windConfig->toArray());
    }

    /**
     * @test
     */
    public function testUpdatewindConfig()
    {
        $windConfig = $this->makewindConfig();
        $editedwindConfig = $this->fakewindConfigData();

        $this->json('PUT', '/api/v1/windConfigs/'.$windConfig->id, $editedwindConfig);

        $this->assertApiResponse($editedwindConfig);
    }

    /**
     * @test
     */
    public function testDeletewindConfig()
    {
        $windConfig = $this->makewindConfig();
        $this->json('DELETE', '/api/v1/windConfigs/'.$windConfig->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/windConfigs/'.$windConfig->id);

        $this->assertResponseStatus(404);
    }
}
