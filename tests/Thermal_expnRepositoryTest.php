<?php

use App\Models\Thermal_expn;
use App\Repositories\Thermal_expnRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Thermal_expnRepositoryTest extends TestCase
{
    use MakeThermal_expnTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var Thermal_expnRepository
     */
    protected $thermalExpnRepo;

    public function setUp()
    {
        parent::setUp();
        $this->thermalExpnRepo = App::make(Thermal_expnRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateThermal_expn()
    {
        $thermalExpn = $this->fakeThermal_expnData();
        $createdThermal_expn = $this->thermalExpnRepo->create($thermalExpn);
        $createdThermal_expn = $createdThermal_expn->toArray();
        $this->assertArrayHasKey('id', $createdThermal_expn);
        $this->assertNotNull($createdThermal_expn['id'], 'Created Thermal_expn must have id specified');
        $this->assertNotNull(Thermal_expn::find($createdThermal_expn['id']), 'Thermal_expn with given id must be in DB');
        $this->assertModelData($thermalExpn, $createdThermal_expn);
    }

    /**
     * @test read
     */
    public function testReadThermal_expn()
    {
        $thermalExpn = $this->makeThermal_expn();
        $dbThermal_expn = $this->thermalExpnRepo->find($thermalExpn->id);
        $dbThermal_expn = $dbThermal_expn->toArray();
        $this->assertModelData($thermalExpn->toArray(), $dbThermal_expn);
    }

    /**
     * @test update
     */
    public function testUpdateThermal_expn()
    {
        $thermalExpn = $this->makeThermal_expn();
        $fakeThermal_expn = $this->fakeThermal_expnData();
        $updatedThermal_expn = $this->thermalExpnRepo->update($fakeThermal_expn, $thermalExpn->id);
        $this->assertModelData($fakeThermal_expn, $updatedThermal_expn->toArray());
        $dbThermal_expn = $this->thermalExpnRepo->find($thermalExpn->id);
        $this->assertModelData($fakeThermal_expn, $dbThermal_expn->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteThermal_expn()
    {
        $thermalExpn = $this->makeThermal_expn();
        $resp = $this->thermalExpnRepo->delete($thermalExpn->id);
        $this->assertTrue($resp);
        $this->assertNull(Thermal_expn::find($thermalExpn->id), 'Thermal_expn should not exist in DB');
    }
}
