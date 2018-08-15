<?php

use App\Models\FuelCostHorizont;
use App\Repositories\FuelCostHorizontRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FuelCostHorizontRepositoryTest extends TestCase
{
    use MakeFuelCostHorizontTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var FuelCostHorizontRepository
     */
    protected $fuelCostHorizontRepo;

    public function setUp()
    {
        parent::setUp();
        $this->fuelCostHorizontRepo = App::make(FuelCostHorizontRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateFuelCostHorizont()
    {
        $fuelCostHorizont = $this->fakeFuelCostHorizontData();
        $createdFuelCostHorizont = $this->fuelCostHorizontRepo->create($fuelCostHorizont);
        $createdFuelCostHorizont = $createdFuelCostHorizont->toArray();
        $this->assertArrayHasKey('id', $createdFuelCostHorizont);
        $this->assertNotNull($createdFuelCostHorizont['id'], 'Created FuelCostHorizont must have id specified');
        $this->assertNotNull(FuelCostHorizont::find($createdFuelCostHorizont['id']), 'FuelCostHorizont with given id must be in DB');
        $this->assertModelData($fuelCostHorizont, $createdFuelCostHorizont);
    }

    /**
     * @test read
     */
    public function testReadFuelCostHorizont()
    {
        $fuelCostHorizont = $this->makeFuelCostHorizont();
        $dbFuelCostHorizont = $this->fuelCostHorizontRepo->find($fuelCostHorizont->id);
        $dbFuelCostHorizont = $dbFuelCostHorizont->toArray();
        $this->assertModelData($fuelCostHorizont->toArray(), $dbFuelCostHorizont);
    }

    /**
     * @test update
     */
    public function testUpdateFuelCostHorizont()
    {
        $fuelCostHorizont = $this->makeFuelCostHorizont();
        $fakeFuelCostHorizont = $this->fakeFuelCostHorizontData();
        $updatedFuelCostHorizont = $this->fuelCostHorizontRepo->update($fakeFuelCostHorizont, $fuelCostHorizont->id);
        $this->assertModelData($fakeFuelCostHorizont, $updatedFuelCostHorizont->toArray());
        $dbFuelCostHorizont = $this->fuelCostHorizontRepo->find($fuelCostHorizont->id);
        $this->assertModelData($fakeFuelCostHorizont, $dbFuelCostHorizont->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteFuelCostHorizont()
    {
        $fuelCostHorizont = $this->makeFuelCostHorizont();
        $resp = $this->fuelCostHorizontRepo->delete($fuelCostHorizont->id);
        $this->assertTrue($resp);
        $this->assertNull(FuelCostHorizont::find($fuelCostHorizont->id), 'FuelCostHorizont should not exist in DB');
    }
}
