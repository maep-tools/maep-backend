<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class windExpnApiTest extends TestCase
{
    use MakewindExpnTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatewindExpn()
    {
        $windExpn = $this->fakewindExpnData();
        $this->json('POST', '/api/v1/windExpns', $windExpn);

        $this->assertApiResponse($windExpn);
    }

    /**
     * @test
     */
    public function testReadwindExpn()
    {
        $windExpn = $this->makewindExpn();
        $this->json('GET', '/api/v1/windExpns/'.$windExpn->id);

        $this->assertApiResponse($windExpn->toArray());
    }

    /**
     * @test
     */
    public function testUpdatewindExpn()
    {
        $windExpn = $this->makewindExpn();
        $editedwindExpn = $this->fakewindExpnData();

        $this->json('PUT', '/api/v1/windExpns/'.$windExpn->id, $editedwindExpn);

        $this->assertApiResponse($editedwindExpn);
    }

    /**
     * @test
     */
    public function testDeletewindExpn()
    {
        $windExpn = $this->makewindExpn();
        $this->json('DELETE', '/api/v1/windExpns/'.$windExpn->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/windExpns/'.$windExpn->id);

        $this->assertResponseStatus(404);
    }
}
