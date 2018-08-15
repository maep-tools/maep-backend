<?php

use App\Models\inflowWind;
use App\Repositories\inflowWindRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class inflowWindRepositoryTest extends TestCase
{
    use MakeinflowWindTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var inflowWindRepository
     */
    protected $inflowWindRepo;

    public function setUp()
    {
        parent::setUp();
        $this->inflowWindRepo = App::make(inflowWindRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateinflowWind()
    {
        $inflowWind = $this->fakeinflowWindData();
        $createdinflowWind = $this->inflowWindRepo->create($inflowWind);
        $createdinflowWind = $createdinflowWind->toArray();
        $this->assertArrayHasKey('id', $createdinflowWind);
        $this->assertNotNull($createdinflowWind['id'], 'Created inflowWind must have id specified');
        $this->assertNotNull(inflowWind::find($createdinflowWind['id']), 'inflowWind with given id must be in DB');
        $this->assertModelData($inflowWind, $createdinflowWind);
    }

    /**
     * @test read
     */
    public function testReadinflowWind()
    {
        $inflowWind = $this->makeinflowWind();
        $dbinflowWind = $this->inflowWindRepo->find($inflowWind->id);
        $dbinflowWind = $dbinflowWind->toArray();
        $this->assertModelData($inflowWind->toArray(), $dbinflowWind);
    }

    /**
     * @test update
     */
    public function testUpdateinflowWind()
    {
        $inflowWind = $this->makeinflowWind();
        $fakeinflowWind = $this->fakeinflowWindData();
        $updatedinflowWind = $this->inflowWindRepo->update($fakeinflowWind, $inflowWind->id);
        $this->assertModelData($fakeinflowWind, $updatedinflowWind->toArray());
        $dbinflowWind = $this->inflowWindRepo->find($inflowWind->id);
        $this->assertModelData($fakeinflowWind, $dbinflowWind->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteinflowWind()
    {
        $inflowWind = $this->makeinflowWind();
        $resp = $this->inflowWindRepo->delete($inflowWind->id);
        $this->assertTrue($resp);
        $this->assertNull(inflowWind::find($inflowWind->id), 'inflowWind should not exist in DB');
    }
}
