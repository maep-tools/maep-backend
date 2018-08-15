<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EntranceStagesApiTest extends TestCase
{
    use MakeEntranceStagesTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateEntranceStages()
    {
        $entranceStages = $this->fakeEntranceStagesData();
        $this->json('POST', '/api/v1/entranceStages', $entranceStages);

        $this->assertApiResponse($entranceStages);
    }

    /**
     * @test
     */
    public function testReadEntranceStages()
    {
        $entranceStages = $this->makeEntranceStages();
        $this->json('GET', '/api/v1/entranceStages/'.$entranceStages->id);

        $this->assertApiResponse($entranceStages->toArray());
    }

    /**
     * @test
     */
    public function testUpdateEntranceStages()
    {
        $entranceStages = $this->makeEntranceStages();
        $editedEntranceStages = $this->fakeEntranceStagesData();

        $this->json('PUT', '/api/v1/entranceStages/'.$entranceStages->id, $editedEntranceStages);

        $this->assertApiResponse($editedEntranceStages);
    }

    /**
     * @test
     */
    public function testDeleteEntranceStages()
    {
        $entranceStages = $this->makeEntranceStages();
        $this->json('DELETE', '/api/v1/entranceStages/'.$entranceStages->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/entranceStages/'.$entranceStages->id);

        $this->assertResponseStatus(404);
    }
}
