<?php

use App\Models\Areas;
use App\Repositories\AreasRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AreasRepositoryTest extends TestCase
{
    use MakeAreasTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var AreasRepository
     */
    protected $areasRepo;

    public function setUp()
    {
        parent::setUp();
        $this->areasRepo = App::make(AreasRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateAreas()
    {
        $areas = $this->fakeAreasData();
        $createdAreas = $this->areasRepo->create($areas);
        $createdAreas = $createdAreas->toArray();
        $this->assertArrayHasKey('id', $createdAreas);
        $this->assertNotNull($createdAreas['id'], 'Created Areas must have id specified');
        $this->assertNotNull(Areas::find($createdAreas['id']), 'Areas with given id must be in DB');
        $this->assertModelData($areas, $createdAreas);
    }

    /**
     * @test read
     */
    public function testReadAreas()
    {
        $areas = $this->makeAreas();
        $dbAreas = $this->areasRepo->find($areas->id);
        $dbAreas = $dbAreas->toArray();
        $this->assertModelData($areas->toArray(), $dbAreas);
    }

    /**
     * @test update
     */
    public function testUpdateAreas()
    {
        $areas = $this->makeAreas();
        $fakeAreas = $this->fakeAreasData();
        $updatedAreas = $this->areasRepo->update($fakeAreas, $areas->id);
        $this->assertModelData($fakeAreas, $updatedAreas->toArray());
        $dbAreas = $this->areasRepo->find($areas->id);
        $this->assertModelData($fakeAreas, $dbAreas->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteAreas()
    {
        $areas = $this->makeAreas();
        $resp = $this->areasRepo->delete($areas->id);
        $this->assertTrue($resp);
        $this->assertNull(Areas::find($areas->id), 'Areas should not exist in DB');
    }
}
