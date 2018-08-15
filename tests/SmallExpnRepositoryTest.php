<?php

use App\Models\SmallExpn;
use App\Repositories\SmallExpnRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SmallExpnRepositoryTest extends TestCase
{
    use MakeSmallExpnTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var SmallExpnRepository
     */
    protected $smallExpnRepo;

    public function setUp()
    {
        parent::setUp();
        $this->smallExpnRepo = App::make(SmallExpnRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateSmallExpn()
    {
        $smallExpn = $this->fakeSmallExpnData();
        $createdSmallExpn = $this->smallExpnRepo->create($smallExpn);
        $createdSmallExpn = $createdSmallExpn->toArray();
        $this->assertArrayHasKey('id', $createdSmallExpn);
        $this->assertNotNull($createdSmallExpn['id'], 'Created SmallExpn must have id specified');
        $this->assertNotNull(SmallExpn::find($createdSmallExpn['id']), 'SmallExpn with given id must be in DB');
        $this->assertModelData($smallExpn, $createdSmallExpn);
    }

    /**
     * @test read
     */
    public function testReadSmallExpn()
    {
        $smallExpn = $this->makeSmallExpn();
        $dbSmallExpn = $this->smallExpnRepo->find($smallExpn->id);
        $dbSmallExpn = $dbSmallExpn->toArray();
        $this->assertModelData($smallExpn->toArray(), $dbSmallExpn);
    }

    /**
     * @test update
     */
    public function testUpdateSmallExpn()
    {
        $smallExpn = $this->makeSmallExpn();
        $fakeSmallExpn = $this->fakeSmallExpnData();
        $updatedSmallExpn = $this->smallExpnRepo->update($fakeSmallExpn, $smallExpn->id);
        $this->assertModelData($fakeSmallExpn, $updatedSmallExpn->toArray());
        $dbSmallExpn = $this->smallExpnRepo->find($smallExpn->id);
        $this->assertModelData($fakeSmallExpn, $dbSmallExpn->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteSmallExpn()
    {
        $smallExpn = $this->makeSmallExpn();
        $resp = $this->smallExpnRepo->delete($smallExpn->id);
        $this->assertTrue($resp);
        $this->assertNull(SmallExpn::find($smallExpn->id), 'SmallExpn should not exist in DB');
    }
}
