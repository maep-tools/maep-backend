<?php

use App\Models\segment;
use App\Repositories\segmentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class segmentRepositoryTest extends TestCase
{
    use MakesegmentTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var segmentRepository
     */
    protected $segmentRepo;

    public function setUp()
    {
        parent::setUp();
        $this->segmentRepo = App::make(segmentRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatesegment()
    {
        $segment = $this->fakesegmentData();
        $createdsegment = $this->segmentRepo->create($segment);
        $createdsegment = $createdsegment->toArray();
        $this->assertArrayHasKey('id', $createdsegment);
        $this->assertNotNull($createdsegment['id'], 'Created segment must have id specified');
        $this->assertNotNull(segment::find($createdsegment['id']), 'segment with given id must be in DB');
        $this->assertModelData($segment, $createdsegment);
    }

    /**
     * @test read
     */
    public function testReadsegment()
    {
        $segment = $this->makesegment();
        $dbsegment = $this->segmentRepo->find($segment->id);
        $dbsegment = $dbsegment->toArray();
        $this->assertModelData($segment->toArray(), $dbsegment);
    }

    /**
     * @test update
     */
    public function testUpdatesegment()
    {
        $segment = $this->makesegment();
        $fakesegment = $this->fakesegmentData();
        $updatedsegment = $this->segmentRepo->update($fakesegment, $segment->id);
        $this->assertModelData($fakesegment, $updatedsegment->toArray());
        $dbsegment = $this->segmentRepo->find($segment->id);
        $this->assertModelData($fakesegment, $dbsegment->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletesegment()
    {
        $segment = $this->makesegment();
        $resp = $this->segmentRepo->delete($segment->id);
        $this->assertTrue($resp);
        $this->assertNull(segment::find($segment->id), 'segment should not exist in DB');
    }
}
