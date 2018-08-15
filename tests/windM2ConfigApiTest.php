<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WindM2ConfigApiTest extends TestCase
{
    use MakeWindM2ConfigTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateWindM2Config()
    {
        $windM2Config = $this->fakeWindM2ConfigData();
        $this->json('POST', '/api/v1/windM2Configs', $windM2Config);

        $this->assertApiResponse($windM2Config);
    }

    /**
     * @test
     */
    public function testReadWindM2Config()
    {
        $windM2Config = $this->makeWindM2Config();
        $this->json('GET', '/api/v1/windM2Configs/'.$windM2Config->id);

        $this->assertApiResponse($windM2Config->toArray());
    }

    /**
     * @test
     */
    public function testUpdateWindM2Config()
    {
        $windM2Config = $this->makeWindM2Config();
        $editedWindM2Config = $this->fakeWindM2ConfigData();

        $this->json('PUT', '/api/v1/windM2Configs/'.$windM2Config->id, $editedWindM2Config);

        $this->assertApiResponse($editedWindM2Config);
    }

    /**
     * @test
     */
    public function testDeleteWindM2Config()
    {
        $windM2Config = $this->makeWindM2Config();
        $this->json('DELETE', '/api/v1/windM2Configs/'.$windM2Config->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/windM2Configs/'.$windM2Config->id);

        $this->assertResponseStatus(404);
    }
}
