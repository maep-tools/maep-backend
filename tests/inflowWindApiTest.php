<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class inflowWindApiTest extends TestCase
{
    use MakeinflowWindTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateinflowWind()
    {
        $inflowWind = $this->fakeinflowWindData();
        $this->json('POST', '/api/v1/inflowWinds', $inflowWind);

        $this->assertApiResponse($inflowWind);
    }

    /**
     * @test
     */
    public function testReadinflowWind()
    {
        $inflowWind = $this->makeinflowWind();
        $this->json('GET', '/api/v1/inflowWinds/'.$inflowWind->id);

        $this->assertApiResponse($inflowWind->toArray());
    }

    /**
     * @test
     */
    public function testUpdateinflowWind()
    {
        $inflowWind = $this->makeinflowWind();
        $editedinflowWind = $this->fakeinflowWindData();

        $this->json('PUT', '/api/v1/inflowWinds/'.$inflowWind->id, $editedinflowWind);

        $this->assertApiResponse($editedinflowWind);
    }

    /**
     * @test
     */
    public function testDeleteinflowWind()
    {
        $inflowWind = $this->makeinflowWind();
        $this->json('DELETE', '/api/v1/inflowWinds/'.$inflowWind->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/inflowWinds/'.$inflowWind->id);

        $this->assertResponseStatus(404);
    }
}
