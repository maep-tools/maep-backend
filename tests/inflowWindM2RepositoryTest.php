<?php

use App\Models\inflowWindM2;
use App\Repositories\inflowWindM2Repository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class inflowWindM2RepositoryTest extends TestCase
{
    use MakeinflowWindM2Trait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var inflowWindM2Repository
     */
    protected $inflowWindM2Repo;

    public function setUp()
    {
        parent::setUp();
        $this->inflowWindM2Repo = App::make(inflowWindM2Repository::class);
    }

    /**
     * @test create
     */
    public function testCreateinflowWindM2()
    {
        $inflowWindM2 = $this->fakeinflowWindM2Data();
        $createdinflowWindM2 = $this->inflowWindM2Repo->create($inflowWindM2);
        $createdinflowWindM2 = $createdinflowWindM2->toArray();
        $this->assertArrayHasKey('id', $createdinflowWindM2);
        $this->assertNotNull($createdinflowWindM2['id'], 'Created inflowWindM2 must have id specified');
        $this->assertNotNull(inflowWindM2::find($createdinflowWindM2['id']), 'inflowWindM2 with given id must be in DB');
        $this->assertModelData($inflowWindM2, $createdinflowWindM2);
    }

    /**
     * @test read
     */
    public function testReadinflowWindM2()
    {
        $inflowWindM2 = $this->makeinflowWindM2();
        $dbinflowWindM2 = $this->inflowWindM2Repo->find($inflowWindM2->id);
        $dbinflowWindM2 = $dbinflowWindM2->toArray();
        $this->assertModelData($inflowWindM2->toArray(), $dbinflowWindM2);
    }

    /**
     * @test update
     */
    public function testUpdateinflowWindM2()
    {
        $inflowWindM2 = $this->makeinflowWindM2();
        $fakeinflowWindM2 = $this->fakeinflowWindM2Data();
        $updatedinflowWindM2 = $this->inflowWindM2Repo->update($fakeinflowWindM2, $inflowWindM2->id);
        $this->assertModelData($fakeinflowWindM2, $updatedinflowWindM2->toArray());
        $dbinflowWindM2 = $this->inflowWindM2Repo->find($inflowWindM2->id);
        $this->assertModelData($fakeinflowWindM2, $dbinflowWindM2->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteinflowWindM2()
    {
        $inflowWindM2 = $this->makeinflowWindM2();
        $resp = $this->inflowWindM2Repo->delete($inflowWindM2->id);
        $this->assertTrue($resp);
        $this->assertNull(inflowWindM2::find($inflowWindM2->id), 'inflowWindM2 should not exist in DB');
    }
}
