<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class horizontApiTest extends TestCase
{
    use MakehorizontTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatehorizont()
    {
        $horizont = $this->fakehorizontData();
        $this->json('POST', '/api/v1/horizonts', $horizont);

        $this->assertApiResponse($horizont);
    }

    /**
     * @test
     */
    public function testReadhorizont()
    {
        $horizont = $this->makehorizont();
        $this->json('GET', '/api/v1/horizonts/'.$horizont->id);

        $this->assertApiResponse($horizont->toArray());
    }

    /**
     * @test
     */
    public function testUpdatehorizont()
    {
        $horizont = $this->makehorizont();
        $editedhorizont = $this->fakehorizontData();

        $this->json('PUT', '/api/v1/horizonts/'.$horizont->id, $editedhorizont);

        $this->assertApiResponse($editedhorizont);
    }

    /**
     * @test
     */
    public function testDeletehorizont()
    {
        $horizont = $this->makehorizont();
        $this->json('DELETE', '/api/v1/horizonts/'.$horizont->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/horizonts/'.$horizont->id);

        $this->assertResponseStatus(404);
    }
}
