<?php

use App\Models\demand;
use App\Repositories\demandRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class demandRepositoryTest extends TestCase
{
    use MakedemandTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var demandRepository
     */
    protected $demandRepo;

    public function setUp()
    {
        parent::setUp();
        $this->demandRepo = App::make(demandRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatedemand()
    {
        $demand = $this->fakedemandData();
        $createddemand = $this->demandRepo->create($demand);
        $createddemand = $createddemand->toArray();
        $this->assertArrayHasKey('id', $createddemand);
        $this->assertNotNull($createddemand['id'], 'Created demand must have id specified');
        $this->assertNotNull(demand::find($createddemand['id']), 'demand with given id must be in DB');
        $this->assertModelData($demand, $createddemand);
    }

    /**
     * @test read
     */
    public function testReaddemand()
    {
        $demand = $this->makedemand();
        $dbdemand = $this->demandRepo->find($demand->id);
        $dbdemand = $dbdemand->toArray();
        $this->assertModelData($demand->toArray(), $dbdemand);
    }

    /**
     * @test update
     */
    public function testUpdatedemand()
    {
        $demand = $this->makedemand();
        $fakedemand = $this->fakedemandData();
        $updateddemand = $this->demandRepo->update($fakedemand, $demand->id);
        $this->assertModelData($fakedemand, $updateddemand->toArray());
        $dbdemand = $this->demandRepo->find($demand->id);
        $this->assertModelData($fakedemand, $dbdemand->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletedemand()
    {
        $demand = $this->makedemand();
        $resp = $this->demandRepo->delete($demand->id);
        $this->assertTrue($resp);
        $this->assertNull(demand::find($demand->id), 'demand should not exist in DB');
    }
}
