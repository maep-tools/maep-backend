<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class inflowApiTest extends TestCase
{
    use MakeinflowTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateinflow()
    {
        $inflow = $this->fakeinflowData();
        $this->json('POST', '/api/v1/inflows', $inflow);

        $this->assertApiResponse($inflow);
    }

    /**
     * @test
     */
    public function testReadinflow()
    {
        $inflow = $this->makeinflow();
        $this->json('GET', '/api/v1/inflows/'.$inflow->id);

        $this->assertApiResponse($inflow->toArray());
    }

    /**
     * @test
     */
    public function testUpdateinflow()
    {
        $inflow = $this->makeinflow();
        $editedinflow = $this->fakeinflowData();

        $this->json('PUT', '/api/v1/inflows/'.$inflow->id, $editedinflow);

        $this->assertApiResponse($editedinflow);
    }

    /**
     * @test
     */
    public function testDeleteinflow()
    {
        $inflow = $this->makeinflow();
        $this->json('DELETE', '/api/v1/inflows/'.$inflow->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/inflows/'.$inflow->id);

        $this->assertResponseStatus(404);
    }
}
