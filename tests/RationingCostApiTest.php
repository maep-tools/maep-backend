<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RationingCostApiTest extends TestCase
{
    use MakeRationingCostTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateRationingCost()
    {
        $rationingCost = $this->fakeRationingCostData();
        $this->json('POST', '/api/v1/rationingCosts', $rationingCost);

        $this->assertApiResponse($rationingCost);
    }

    /**
     * @test
     */
    public function testReadRationingCost()
    {
        $rationingCost = $this->makeRationingCost();
        $this->json('GET', '/api/v1/rationingCosts/'.$rationingCost->id);

        $this->assertApiResponse($rationingCost->toArray());
    }

    /**
     * @test
     */
    public function testUpdateRationingCost()
    {
        $rationingCost = $this->makeRationingCost();
        $editedRationingCost = $this->fakeRationingCostData();

        $this->json('PUT', '/api/v1/rationingCosts/'.$rationingCost->id, $editedRationingCost);

        $this->assertApiResponse($editedRationingCost);
    }

    /**
     * @test
     */
    public function testDeleteRationingCost()
    {
        $rationingCost = $this->makeRationingCost();
        $this->json('DELETE', '/api/v1/rationingCosts/'.$rationingCost->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/rationingCosts/'.$rationingCost->id);

        $this->assertResponseStatus(404);
    }
}
