<?php

use App\Models\HidroExpn;
use App\Repositories\HidroExpnRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HidroExpnRepositoryTest extends TestCase
{
    use MakeHidroExpnTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var HidroExpnRepository
     */
    protected $hidroExpnRepo;

    public function setUp()
    {
        parent::setUp();
        $this->hidroExpnRepo = App::make(HidroExpnRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateHidroExpn()
    {
        $hidroExpn = $this->fakeHidroExpnData();
        $createdHidroExpn = $this->hidroExpnRepo->create($hidroExpn);
        $createdHidroExpn = $createdHidroExpn->toArray();
        $this->assertArrayHasKey('id', $createdHidroExpn);
        $this->assertNotNull($createdHidroExpn['id'], 'Created HidroExpn must have id specified');
        $this->assertNotNull(HidroExpn::find($createdHidroExpn['id']), 'HidroExpn with given id must be in DB');
        $this->assertModelData($hidroExpn, $createdHidroExpn);
    }

    /**
     * @test read
     */
    public function testReadHidroExpn()
    {
        $hidroExpn = $this->makeHidroExpn();
        $dbHidroExpn = $this->hidroExpnRepo->find($hidroExpn->id);
        $dbHidroExpn = $dbHidroExpn->toArray();
        $this->assertModelData($hidroExpn->toArray(), $dbHidroExpn);
    }

    /**
     * @test update
     */
    public function testUpdateHidroExpn()
    {
        $hidroExpn = $this->makeHidroExpn();
        $fakeHidroExpn = $this->fakeHidroExpnData();
        $updatedHidroExpn = $this->hidroExpnRepo->update($fakeHidroExpn, $hidroExpn->id);
        $this->assertModelData($fakeHidroExpn, $updatedHidroExpn->toArray());
        $dbHidroExpn = $this->hidroExpnRepo->find($hidroExpn->id);
        $this->assertModelData($fakeHidroExpn, $dbHidroExpn->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteHidroExpn()
    {
        $hidroExpn = $this->makeHidroExpn();
        $resp = $this->hidroExpnRepo->delete($hidroExpn->id);
        $this->assertTrue($resp);
        $this->assertNull(HidroExpn::find($hidroExpn->id), 'HidroExpn should not exist in DB');
    }
}
