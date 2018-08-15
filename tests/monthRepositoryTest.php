<?php

use App\Models\month;
use App\Repositories\monthRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class monthRepositoryTest extends TestCase
{
    use MakemonthTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var monthRepository
     */
    protected $monthRepo;

    public function setUp()
    {
        parent::setUp();
        $this->monthRepo = App::make(monthRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatemonth()
    {
        $month = $this->fakemonthData();
        $createdmonth = $this->monthRepo->create($month);
        $createdmonth = $createdmonth->toArray();
        $this->assertArrayHasKey('id', $createdmonth);
        $this->assertNotNull($createdmonth['id'], 'Created month must have id specified');
        $this->assertNotNull(month::find($createdmonth['id']), 'month with given id must be in DB');
        $this->assertModelData($month, $createdmonth);
    }

    /**
     * @test read
     */
    public function testReadmonth()
    {
        $month = $this->makemonth();
        $dbmonth = $this->monthRepo->find($month->id);
        $dbmonth = $dbmonth->toArray();
        $this->assertModelData($month->toArray(), $dbmonth);
    }

    /**
     * @test update
     */
    public function testUpdatemonth()
    {
        $month = $this->makemonth();
        $fakemonth = $this->fakemonthData();
        $updatedmonth = $this->monthRepo->update($fakemonth, $month->id);
        $this->assertModelData($fakemonth, $updatedmonth->toArray());
        $dbmonth = $this->monthRepo->find($month->id);
        $this->assertModelData($fakemonth, $dbmonth->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletemonth()
    {
        $month = $this->makemonth();
        $resp = $this->monthRepo->delete($month->id);
        $this->assertTrue($resp);
        $this->assertNull(month::find($month->id), 'month should not exist in DB');
    }
}
