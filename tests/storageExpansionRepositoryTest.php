<?php

use App\Models\storageExpansion;
use App\Repositories\storageExpansionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class storageExpansionRepositoryTest extends TestCase
{
    use MakestorageExpansionTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var storageExpansionRepository
     */
    protected $storageExpansionRepo;

    public function setUp()
    {
        parent::setUp();
        $this->storageExpansionRepo = App::make(storageExpansionRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatestorageExpansion()
    {
        $storageExpansion = $this->fakestorageExpansionData();
        $createdstorageExpansion = $this->storageExpansionRepo->create($storageExpansion);
        $createdstorageExpansion = $createdstorageExpansion->toArray();
        $this->assertArrayHasKey('id', $createdstorageExpansion);
        $this->assertNotNull($createdstorageExpansion['id'], 'Created storageExpansion must have id specified');
        $this->assertNotNull(storageExpansion::find($createdstorageExpansion['id']), 'storageExpansion with given id must be in DB');
        $this->assertModelData($storageExpansion, $createdstorageExpansion);
    }

    /**
     * @test read
     */
    public function testReadstorageExpansion()
    {
        $storageExpansion = $this->makestorageExpansion();
        $dbstorageExpansion = $this->storageExpansionRepo->find($storageExpansion->id);
        $dbstorageExpansion = $dbstorageExpansion->toArray();
        $this->assertModelData($storageExpansion->toArray(), $dbstorageExpansion);
    }

    /**
     * @test update
     */
    public function testUpdatestorageExpansion()
    {
        $storageExpansion = $this->makestorageExpansion();
        $fakestorageExpansion = $this->fakestorageExpansionData();
        $updatedstorageExpansion = $this->storageExpansionRepo->update($fakestorageExpansion, $storageExpansion->id);
        $this->assertModelData($fakestorageExpansion, $updatedstorageExpansion->toArray());
        $dbstorageExpansion = $this->storageExpansionRepo->find($storageExpansion->id);
        $this->assertModelData($fakestorageExpansion, $dbstorageExpansion->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletestorageExpansion()
    {
        $storageExpansion = $this->makestorageExpansion();
        $resp = $this->storageExpansionRepo->delete($storageExpansion->id);
        $this->assertTrue($resp);
        $this->assertNull(storageExpansion::find($storageExpansion->id), 'storageExpansion should not exist in DB');
    }
}
