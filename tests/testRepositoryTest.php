<?php

use App\Models\test;
use App\Repositories\testRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class testRepositoryTest extends TestCase
{
    use MaketestTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var testRepository
     */
    protected $testRepo;

    public function setUp()
    {
        parent::setUp();
        $this->testRepo = App::make(testRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatetest()
    {
        $test = $this->faketestData();
        $createdtest = $this->testRepo->create($test);
        $createdtest = $createdtest->toArray();
        $this->assertArrayHasKey('id', $createdtest);
        $this->assertNotNull($createdtest['id'], 'Created test must have id specified');
        $this->assertNotNull(test::find($createdtest['id']), 'test with given id must be in DB');
        $this->assertModelData($test, $createdtest);
    }

    /**
     * @test read
     */
    public function testReadtest()
    {
        $test = $this->maketest();
        $dbtest = $this->testRepo->find($test->id);
        $dbtest = $dbtest->toArray();
        $this->assertModelData($test->toArray(), $dbtest);
    }

    /**
     * @test update
     */
    public function testUpdatetest()
    {
        $test = $this->maketest();
        $faketest = $this->faketestData();
        $updatedtest = $this->testRepo->update($faketest, $test->id);
        $this->assertModelData($faketest, $updatedtest->toArray());
        $dbtest = $this->testRepo->find($test->id);
        $this->assertModelData($faketest, $dbtest->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletetest()
    {
        $test = $this->maketest();
        $resp = $this->testRepo->delete($test->id);
        $this->assertTrue($resp);
        $this->assertNull(test::find($test->id), 'test should not exist in DB');
    }
}
