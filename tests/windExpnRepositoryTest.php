<?php

use App\Models\windExpn;
use App\Repositories\windExpnRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class windExpnRepositoryTest extends TestCase
{
    use MakewindExpnTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var windExpnRepository
     */
    protected $windExpnRepo;

    public function setUp()
    {
        parent::setUp();
        $this->windExpnRepo = App::make(windExpnRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatewindExpn()
    {
        $windExpn = $this->fakewindExpnData();
        $createdwindExpn = $this->windExpnRepo->create($windExpn);
        $createdwindExpn = $createdwindExpn->toArray();
        $this->assertArrayHasKey('id', $createdwindExpn);
        $this->assertNotNull($createdwindExpn['id'], 'Created windExpn must have id specified');
        $this->assertNotNull(windExpn::find($createdwindExpn['id']), 'windExpn with given id must be in DB');
        $this->assertModelData($windExpn, $createdwindExpn);
    }

    /**
     * @test read
     */
    public function testReadwindExpn()
    {
        $windExpn = $this->makewindExpn();
        $dbwindExpn = $this->windExpnRepo->find($windExpn->id);
        $dbwindExpn = $dbwindExpn->toArray();
        $this->assertModelData($windExpn->toArray(), $dbwindExpn);
    }

    /**
     * @test update
     */
    public function testUpdatewindExpn()
    {
        $windExpn = $this->makewindExpn();
        $fakewindExpn = $this->fakewindExpnData();
        $updatedwindExpn = $this->windExpnRepo->update($fakewindExpn, $windExpn->id);
        $this->assertModelData($fakewindExpn, $updatedwindExpn->toArray());
        $dbwindExpn = $this->windExpnRepo->find($windExpn->id);
        $this->assertModelData($fakewindExpn, $dbwindExpn->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletewindExpn()
    {
        $windExpn = $this->makewindExpn();
        $resp = $this->windExpnRepo->delete($windExpn->id);
        $this->assertTrue($resp);
        $this->assertNull(windExpn::find($windExpn->id), 'windExpn should not exist in DB');
    }
}
