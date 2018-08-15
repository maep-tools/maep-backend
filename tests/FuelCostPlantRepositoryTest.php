<?php

use App\Models\FuelCostPlant;
use App\Repositories\FuelCostPlantRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FuelCostPlantRepositoryTest extends TestCase
{
    use MakeFuelCostPlantTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var FuelCostPlantRepository
     */
    protected $fuelCostPlantRepo;

    public function setUp()
    {
        parent::setUp();
        $this->fuelCostPlantRepo = App::make(FuelCostPlantRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateFuelCostPlant()
    {
        $fuelCostPlant = $this->fakeFuelCostPlantData();
        $createdFuelCostPlant = $this->fuelCostPlantRepo->create($fuelCostPlant);
        $createdFuelCostPlant = $createdFuelCostPlant->toArray();
        $this->assertArrayHasKey('id', $createdFuelCostPlant);
        $this->assertNotNull($createdFuelCostPlant['id'], 'Created FuelCostPlant must have id specified');
        $this->assertNotNull(FuelCostPlant::find($createdFuelCostPlant['id']), 'FuelCostPlant with given id must be in DB');
        $this->assertModelData($fuelCostPlant, $createdFuelCostPlant);
    }

    /**
     * @test read
     */
    public function testReadFuelCostPlant()
    {
        $fuelCostPlant = $this->makeFuelCostPlant();
        $dbFuelCostPlant = $this->fuelCostPlantRepo->find($fuelCostPlant->id);
        $dbFuelCostPlant = $dbFuelCostPlant->toArray();
        $this->assertModelData($fuelCostPlant->toArray(), $dbFuelCostPlant);
    }

    /**
     * @test update
     */
    public function testUpdateFuelCostPlant()
    {
        $fuelCostPlant = $this->makeFuelCostPlant();
        $fakeFuelCostPlant = $this->fakeFuelCostPlantData();
        $updatedFuelCostPlant = $this->fuelCostPlantRepo->update($fakeFuelCostPlant, $fuelCostPlant->id);
        $this->assertModelData($fakeFuelCostPlant, $updatedFuelCostPlant->toArray());
        $dbFuelCostPlant = $this->fuelCostPlantRepo->find($fuelCostPlant->id);
        $this->assertModelData($fakeFuelCostPlant, $dbFuelCostPlant->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteFuelCostPlant()
    {
        $fuelCostPlant = $this->makeFuelCostPlant();
        $resp = $this->fuelCostPlantRepo->delete($fuelCostPlant->id);
        $this->assertTrue($resp);
        $this->assertNull(FuelCostPlant::find($fuelCostPlant->id), 'FuelCostPlant should not exist in DB');
    }
}
