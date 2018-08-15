<?php

use App\Models\SpeedIndicesM2;
use App\Repositories\SpeedIndicesM2Repository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SpeedIndicesM2RepositoryTest extends TestCase
{
    use MakeSpeedIndicesM2Trait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var SpeedIndicesM2Repository
     */
    protected $speedIndicesM2Repo;

    public function setUp()
    {
        parent::setUp();
        $this->speedIndicesM2Repo = App::make(SpeedIndicesM2Repository::class);
    }

    /**
     * @test create
     */
    public function testCreateSpeedIndicesM2()
    {
        $speedIndicesM2 = $this->fakeSpeedIndicesM2Data();
        $createdSpeedIndicesM2 = $this->speedIndicesM2Repo->create($speedIndicesM2);
        $createdSpeedIndicesM2 = $createdSpeedIndicesM2->toArray();
        $this->assertArrayHasKey('id', $createdSpeedIndicesM2);
        $this->assertNotNull($createdSpeedIndicesM2['id'], 'Created SpeedIndicesM2 must have id specified');
        $this->assertNotNull(SpeedIndicesM2::find($createdSpeedIndicesM2['id']), 'SpeedIndicesM2 with given id must be in DB');
        $this->assertModelData($speedIndicesM2, $createdSpeedIndicesM2);
    }

    /**
     * @test read
     */
    public function testReadSpeedIndicesM2()
    {
        $speedIndicesM2 = $this->makeSpeedIndicesM2();
        $dbSpeedIndicesM2 = $this->speedIndicesM2Repo->find($speedIndicesM2->id);
        $dbSpeedIndicesM2 = $dbSpeedIndicesM2->toArray();
        $this->assertModelData($speedIndicesM2->toArray(), $dbSpeedIndicesM2);
    }

    /**
     * @test update
     */
    public function testUpdateSpeedIndicesM2()
    {
        $speedIndicesM2 = $this->makeSpeedIndicesM2();
        $fakeSpeedIndicesM2 = $this->fakeSpeedIndicesM2Data();
        $updatedSpeedIndicesM2 = $this->speedIndicesM2Repo->update($fakeSpeedIndicesM2, $speedIndicesM2->id);
        $this->assertModelData($fakeSpeedIndicesM2, $updatedSpeedIndicesM2->toArray());
        $dbSpeedIndicesM2 = $this->speedIndicesM2Repo->find($speedIndicesM2->id);
        $this->assertModelData($fakeSpeedIndicesM2, $dbSpeedIndicesM2->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteSpeedIndicesM2()
    {
        $speedIndicesM2 = $this->makeSpeedIndicesM2();
        $resp = $this->speedIndicesM2Repo->delete($speedIndicesM2->id);
        $this->assertTrue($resp);
        $this->assertNull(SpeedIndicesM2::find($speedIndicesM2->id), 'SpeedIndicesM2 should not exist in DB');
    }
}
