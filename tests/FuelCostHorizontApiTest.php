<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FuelCostHorizontApiTest extends TestCase
{
    use MakeFuelCostHorizontTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateFuelCostHorizont()
    {
        $fuelCostHorizont = $this->fakeFuelCostHorizontData();
        $this->json('POST', '/api/v1/fuelCostHorizonts', $fuelCostHorizont);

        $this->assertApiResponse($fuelCostHorizont);
    }

    /**
     * @test
     */
    public function testReadFuelCostHorizont()
    {
        $fuelCostHorizont = $this->makeFuelCostHorizont();
        $this->json('GET', '/api/v1/fuelCostHorizonts/'.$fuelCostHorizont->id);

        $this->assertApiResponse($fuelCostHorizont->toArray());
    }

    /**
     * @test
     */
    public function testUpdateFuelCostHorizont()
    {
        $fuelCostHorizont = $this->makeFuelCostHorizont();
        $editedFuelCostHorizont = $this->fakeFuelCostHorizontData();

        $this->json('PUT', '/api/v1/fuelCostHorizonts/'.$fuelCostHorizont->id, $editedFuelCostHorizont);

        $this->assertApiResponse($editedFuelCostHorizont);
    }

    /**
     * @test
     */
    public function testDeleteFuelCostHorizont()
    {
        $fuelCostHorizont = $this->makeFuelCostHorizont();
        $this->json('DELETE', '/api/v1/fuelCostHorizonts/'.$fuelCostHorizont->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/fuelCostHorizonts/'.$fuelCostHorizont->id);

        $this->assertResponseStatus(404);
    }
}
