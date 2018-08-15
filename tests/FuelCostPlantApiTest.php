<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FuelCostPlantApiTest extends TestCase
{
    use MakeFuelCostPlantTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateFuelCostPlant()
    {
        $fuelCostPlant = $this->fakeFuelCostPlantData();
        $this->json('POST', '/api/v1/fuelCostPlants', $fuelCostPlant);

        $this->assertApiResponse($fuelCostPlant);
    }

    /**
     * @test
     */
    public function testReadFuelCostPlant()
    {
        $fuelCostPlant = $this->makeFuelCostPlant();
        $this->json('GET', '/api/v1/fuelCostPlants/'.$fuelCostPlant->id);

        $this->assertApiResponse($fuelCostPlant->toArray());
    }

    /**
     * @test
     */
    public function testUpdateFuelCostPlant()
    {
        $fuelCostPlant = $this->makeFuelCostPlant();
        $editedFuelCostPlant = $this->fakeFuelCostPlantData();

        $this->json('PUT', '/api/v1/fuelCostPlants/'.$fuelCostPlant->id, $editedFuelCostPlant);

        $this->assertApiResponse($editedFuelCostPlant);
    }

    /**
     * @test
     */
    public function testDeleteFuelCostPlant()
    {
        $fuelCostPlant = $this->makeFuelCostPlant();
        $this->json('DELETE', '/api/v1/fuelCostPlants/'.$fuelCostPlant->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/fuelCostPlants/'.$fuelCostPlant->id);

        $this->assertResponseStatus(404);
    }
}
