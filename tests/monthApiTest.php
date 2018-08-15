<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class monthApiTest extends TestCase
{
    use MakemonthTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatemonth()
    {
        $month = $this->fakemonthData();
        $this->json('POST', '/api/v1/months', $month);

        $this->assertApiResponse($month);
    }

    /**
     * @test
     */
    public function testReadmonth()
    {
        $month = $this->makemonth();
        $this->json('GET', '/api/v1/months/'.$month->id);

        $this->assertApiResponse($month->toArray());
    }

    /**
     * @test
     */
    public function testUpdatemonth()
    {
        $month = $this->makemonth();
        $editedmonth = $this->fakemonthData();

        $this->json('PUT', '/api/v1/months/'.$month->id, $editedmonth);

        $this->assertApiResponse($editedmonth);
    }

    /**
     * @test
     */
    public function testDeletemonth()
    {
        $month = $this->makemonth();
        $this->json('DELETE', '/api/v1/months/'.$month->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/months/'.$month->id);

        $this->assertResponseStatus(404);
    }
}
