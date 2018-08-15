<?php

use App\Models\EntranceStages;
use App\Repositories\EntranceStagesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EntranceStagesRepositoryTest extends TestCase
{
    use MakeEntranceStagesTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var EntranceStagesRepository
     */
    protected $entranceStagesRepo;

    public function setUp()
    {
        parent::setUp();
        $this->entranceStagesRepo = App::make(EntranceStagesRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateEntranceStages()
    {
        $entranceStages = $this->fakeEntranceStagesData();
        $createdEntranceStages = $this->entranceStagesRepo->create($entranceStages);
        $createdEntranceStages = $createdEntranceStages->toArray();
        $this->assertArrayHasKey('id', $createdEntranceStages);
        $this->assertNotNull($createdEntranceStages['id'], 'Created EntranceStages must have id specified');
        $this->assertNotNull(EntranceStages::find($createdEntranceStages['id']), 'EntranceStages with given id must be in DB');
        $this->assertModelData($entranceStages, $createdEntranceStages);
    }

    /**
     * @test read
     */
    public function testReadEntranceStages()
    {
        $entranceStages = $this->makeEntranceStages();
        $dbEntranceStages = $this->entranceStagesRepo->find($entranceStages->id);
        $dbEntranceStages = $dbEntranceStages->toArray();
        $this->assertModelData($entranceStages->toArray(), $dbEntranceStages);
    }

    /**
     * @test update
     */
    public function testUpdateEntranceStages()
    {
        $entranceStages = $this->makeEntranceStages();
        $fakeEntranceStages = $this->fakeEntranceStagesData();
        $updatedEntranceStages = $this->entranceStagesRepo->update($fakeEntranceStages, $entranceStages->id);
        $this->assertModelData($fakeEntranceStages, $updatedEntranceStages->toArray());
        $dbEntranceStages = $this->entranceStagesRepo->find($entranceStages->id);
        $this->assertModelData($fakeEntranceStages, $dbEntranceStages->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteEntranceStages()
    {
        $entranceStages = $this->makeEntranceStages();
        $resp = $this->entranceStagesRepo->delete($entranceStages->id);
        $this->assertTrue($resp);
        $this->assertNull(EntranceStages::find($entranceStages->id), 'EntranceStages should not exist in DB');
    }
}
