<?php

use App\Models\RationingCost;
use App\Repositories\RationingCostRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RationingCostRepositoryTest extends TestCase
{
    use MakeRationingCostTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var RationingCostRepository
     */
    protected $rationingCostRepo;

    public function setUp()
    {
        parent::setUp();
        $this->rationingCostRepo = App::make(RationingCostRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateRationingCost()
    {
        $rationingCost = $this->fakeRationingCostData();
        $createdRationingCost = $this->rationingCostRepo->create($rationingCost);
        $createdRationingCost = $createdRationingCost->toArray();
        $this->assertArrayHasKey('id', $createdRationingCost);
        $this->assertNotNull($createdRationingCost['id'], 'Created RationingCost must have id specified');
        $this->assertNotNull(RationingCost::find($createdRationingCost['id']), 'RationingCost with given id must be in DB');
        $this->assertModelData($rationingCost, $createdRationingCost);
    }

    /**
     * @test read
     */
    public function testReadRationingCost()
    {
        $rationingCost = $this->makeRationingCost();
        $dbRationingCost = $this->rationingCostRepo->find($rationingCost->id);
        $dbRationingCost = $dbRationingCost->toArray();
        $this->assertModelData($rationingCost->toArray(), $dbRationingCost);
    }

    /**
     * @test update
     */
    public function testUpdateRationingCost()
    {
        $rationingCost = $this->makeRationingCost();
        $fakeRationingCost = $this->fakeRationingCostData();
        $updatedRationingCost = $this->rationingCostRepo->update($fakeRationingCost, $rationingCost->id);
        $this->assertModelData($fakeRationingCost, $updatedRationingCost->toArray());
        $dbRationingCost = $this->rationingCostRepo->find($rationingCost->id);
        $this->assertModelData($fakeRationingCost, $dbRationingCost->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteRationingCost()
    {
        $rationingCost = $this->makeRationingCost();
        $resp = $this->rationingCostRepo->delete($rationingCost->id);
        $this->assertTrue($resp);
        $this->assertNull(RationingCost::find($rationingCost->id), 'RationingCost should not exist in DB');
    }
}
