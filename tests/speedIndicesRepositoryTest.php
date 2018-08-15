<?php

use App\Models\speedIndices;
use App\Repositories\speedIndicesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class speedIndicesRepositoryTest extends TestCase
{
    use MakespeedIndicesTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var speedIndicesRepository
     */
    protected $speedIndicesRepo;

    public function setUp()
    {
        parent::setUp();
        $this->speedIndicesRepo = App::make(speedIndicesRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatespeedIndices()
    {
        $speedIndices = $this->fakespeedIndicesData();
        $createdspeedIndices = $this->speedIndicesRepo->create($speedIndices);
        $createdspeedIndices = $createdspeedIndices->toArray();
        $this->assertArrayHasKey('id', $createdspeedIndices);
        $this->assertNotNull($createdspeedIndices['id'], 'Created speedIndices must have id specified');
        $this->assertNotNull(speedIndices::find($createdspeedIndices['id']), 'speedIndices with given id must be in DB');
        $this->assertModelData($speedIndices, $createdspeedIndices);
    }

    /**
     * @test read
     */
    public function testReadspeedIndices()
    {
        $speedIndices = $this->makespeedIndices();
        $dbspeedIndices = $this->speedIndicesRepo->find($speedIndices->id);
        $dbspeedIndices = $dbspeedIndices->toArray();
        $this->assertModelData($speedIndices->toArray(), $dbspeedIndices);
    }

    /**
     * @test update
     */
    public function testUpdatespeedIndices()
    {
        $speedIndices = $this->makespeedIndices();
        $fakespeedIndices = $this->fakespeedIndicesData();
        $updatedspeedIndices = $this->speedIndicesRepo->update($fakespeedIndices, $speedIndices->id);
        $this->assertModelData($fakespeedIndices, $updatedspeedIndices->toArray());
        $dbspeedIndices = $this->speedIndicesRepo->find($speedIndices->id);
        $this->assertModelData($fakespeedIndices, $dbspeedIndices->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletespeedIndices()
    {
        $speedIndices = $this->makespeedIndices();
        $resp = $this->speedIndicesRepo->delete($speedIndices->id);
        $this->assertTrue($resp);
        $this->assertNull(speedIndices::find($speedIndices->id), 'speedIndices should not exist in DB');
    }
}
