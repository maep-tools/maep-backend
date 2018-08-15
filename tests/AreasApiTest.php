<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AreasApiTest extends TestCase
{
    use MakeAreasTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateAreas()
    {
        $areas = $this->fakeAreasData();
        $this->json('POST', '/api/v1/areas', $areas);

        $this->assertApiResponse($areas);
    }

    /**
     * @test
     */
    public function testReadAreas()
    {
        $areas = $this->makeAreas();
        $this->json('GET', '/api/v1/areas/'.$areas->id);

        $this->assertApiResponse($areas->toArray());
    }

    /**
     * @test
     */
    public function testUpdateAreas()
    {
        $areas = $this->makeAreas();
        $editedAreas = $this->fakeAreasData();

        $this->json('PUT', '/api/v1/areas/'.$areas->id, $editedAreas);

        $this->assertApiResponse($editedAreas);
    }

    /**
     * @test
     */
    public function testDeleteAreas()
    {
        $areas = $this->makeAreas();
        $this->json('DELETE', '/api/v1/areas/'.$areas->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/areas/'.$areas->id);

        $this->assertResponseStatus(404);
    }
}
