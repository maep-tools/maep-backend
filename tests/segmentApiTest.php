<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class segmentApiTest extends TestCase
{
    use MakesegmentTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatesegment()
    {
        $segment = $this->fakesegmentData();
        $this->json('POST', '/api/v1/segments', $segment);

        $this->assertApiResponse($segment);
    }

    /**
     * @test
     */
    public function testReadsegment()
    {
        $segment = $this->makesegment();
        $this->json('GET', '/api/v1/segments/'.$segment->id);

        $this->assertApiResponse($segment->toArray());
    }

    /**
     * @test
     */
    public function testUpdatesegment()
    {
        $segment = $this->makesegment();
        $editedsegment = $this->fakesegmentData();

        $this->json('PUT', '/api/v1/segments/'.$segment->id, $editedsegment);

        $this->assertApiResponse($editedsegment);
    }

    /**
     * @test
     */
    public function testDeletesegment()
    {
        $segment = $this->makesegment();
        $this->json('DELETE', '/api/v1/segments/'.$segment->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/segments/'.$segment->id);

        $this->assertResponseStatus(404);
    }
}
