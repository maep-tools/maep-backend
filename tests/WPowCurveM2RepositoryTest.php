<?php

use App\Models\WPowCurveM2;
use App\Repositories\WPowCurveM2Repository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WPowCurveM2RepositoryTest extends TestCase
{
    use MakeWPowCurveM2Trait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var WPowCurveM2Repository
     */
    protected $wPowCurveM2Repo;

    public function setUp()
    {
        parent::setUp();
        $this->wPowCurveM2Repo = App::make(WPowCurveM2Repository::class);
    }

    /**
     * @test create
     */
    public function testCreateWPowCurveM2()
    {
        $wPowCurveM2 = $this->fakeWPowCurveM2Data();
        $createdWPowCurveM2 = $this->wPowCurveM2Repo->create($wPowCurveM2);
        $createdWPowCurveM2 = $createdWPowCurveM2->toArray();
        $this->assertArrayHasKey('id', $createdWPowCurveM2);
        $this->assertNotNull($createdWPowCurveM2['id'], 'Created WPowCurveM2 must have id specified');
        $this->assertNotNull(WPowCurveM2::find($createdWPowCurveM2['id']), 'WPowCurveM2 with given id must be in DB');
        $this->assertModelData($wPowCurveM2, $createdWPowCurveM2);
    }

    /**
     * @test read
     */
    public function testReadWPowCurveM2()
    {
        $wPowCurveM2 = $this->makeWPowCurveM2();
        $dbWPowCurveM2 = $this->wPowCurveM2Repo->find($wPowCurveM2->id);
        $dbWPowCurveM2 = $dbWPowCurveM2->toArray();
        $this->assertModelData($wPowCurveM2->toArray(), $dbWPowCurveM2);
    }

    /**
     * @test update
     */
    public function testUpdateWPowCurveM2()
    {
        $wPowCurveM2 = $this->makeWPowCurveM2();
        $fakeWPowCurveM2 = $this->fakeWPowCurveM2Data();
        $updatedWPowCurveM2 = $this->wPowCurveM2Repo->update($fakeWPowCurveM2, $wPowCurveM2->id);
        $this->assertModelData($fakeWPowCurveM2, $updatedWPowCurveM2->toArray());
        $dbWPowCurveM2 = $this->wPowCurveM2Repo->find($wPowCurveM2->id);
        $this->assertModelData($fakeWPowCurveM2, $dbWPowCurveM2->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteWPowCurveM2()
    {
        $wPowCurveM2 = $this->makeWPowCurveM2();
        $resp = $this->wPowCurveM2Repo->delete($wPowCurveM2->id);
        $this->assertTrue($resp);
        $this->assertNull(WPowCurveM2::find($wPowCurveM2->id), 'WPowCurveM2 should not exist in DB');
    }
}
