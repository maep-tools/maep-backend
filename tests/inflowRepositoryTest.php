<?php

use App\Models\inflow;
use App\Repositories\inflowRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class inflowRepositoryTest extends TestCase
{
    use MakeinflowTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var inflowRepository
     */
    protected $inflowRepo;

    public function setUp()
    {
        parent::setUp();
        $this->inflowRepo = App::make(inflowRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateinflow()
    {
        $inflow = $this->fakeinflowData();
        $createdinflow = $this->inflowRepo->create($inflow);
        $createdinflow = $createdinflow->toArray();
        $this->assertArrayHasKey('id', $createdinflow);
        $this->assertNotNull($createdinflow['id'], 'Created inflow must have id specified');
        $this->assertNotNull(inflow::find($createdinflow['id']), 'inflow with given id must be in DB');
        $this->assertModelData($inflow, $createdinflow);
    }

    /**
     * @test read
     */
    public function testReadinflow()
    {
        $inflow = $this->makeinflow();
        $dbinflow = $this->inflowRepo->find($inflow->id);
        $dbinflow = $dbinflow->toArray();
        $this->assertModelData($inflow->toArray(), $dbinflow);
    }

    /**
     * @test update
     */
    public function testUpdateinflow()
    {
        $inflow = $this->makeinflow();
        $fakeinflow = $this->fakeinflowData();
        $updatedinflow = $this->inflowRepo->update($fakeinflow, $inflow->id);
        $this->assertModelData($fakeinflow, $updatedinflow->toArray());
        $dbinflow = $this->inflowRepo->find($inflow->id);
        $this->assertModelData($fakeinflow, $dbinflow->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteinflow()
    {
        $inflow = $this->makeinflow();
        $resp = $this->inflowRepo->delete($inflow->id);
        $this->assertTrue($resp);
        $this->assertNull(inflow::find($inflow->id), 'inflow should not exist in DB');
    }
}
