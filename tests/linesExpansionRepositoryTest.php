<?php

use App\Models\linesExpansion;
use App\Repositories\linesExpansionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class linesExpansionRepositoryTest extends TestCase
{
    use MakelinesExpansionTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var linesExpansionRepository
     */
    protected $linesExpansionRepo;

    public function setUp()
    {
        parent::setUp();
        $this->linesExpansionRepo = App::make(linesExpansionRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatelinesExpansion()
    {
        $linesExpansion = $this->fakelinesExpansionData();
        $createdlinesExpansion = $this->linesExpansionRepo->create($linesExpansion);
        $createdlinesExpansion = $createdlinesExpansion->toArray();
        $this->assertArrayHasKey('id', $createdlinesExpansion);
        $this->assertNotNull($createdlinesExpansion['id'], 'Created linesExpansion must have id specified');
        $this->assertNotNull(linesExpansion::find($createdlinesExpansion['id']), 'linesExpansion with given id must be in DB');
        $this->assertModelData($linesExpansion, $createdlinesExpansion);
    }

    /**
     * @test read
     */
    public function testReadlinesExpansion()
    {
        $linesExpansion = $this->makelinesExpansion();
        $dblinesExpansion = $this->linesExpansionRepo->find($linesExpansion->id);
        $dblinesExpansion = $dblinesExpansion->toArray();
        $this->assertModelData($linesExpansion->toArray(), $dblinesExpansion);
    }

    /**
     * @test update
     */
    public function testUpdatelinesExpansion()
    {
        $linesExpansion = $this->makelinesExpansion();
        $fakelinesExpansion = $this->fakelinesExpansionData();
        $updatedlinesExpansion = $this->linesExpansionRepo->update($fakelinesExpansion, $linesExpansion->id);
        $this->assertModelData($fakelinesExpansion, $updatedlinesExpansion->toArray());
        $dblinesExpansion = $this->linesExpansionRepo->find($linesExpansion->id);
        $this->assertModelData($fakelinesExpansion, $dblinesExpansion->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletelinesExpansion()
    {
        $linesExpansion = $this->makelinesExpansion();
        $resp = $this->linesExpansionRepo->delete($linesExpansion->id);
        $this->assertTrue($resp);
        $this->assertNull(linesExpansion::find($linesExpansion->id), 'linesExpansion should not exist in DB');
    }
}
