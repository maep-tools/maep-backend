<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class linesApiTest extends TestCase
{
    use MakelinesTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatelines()
    {
        $lines = $this->fakelinesData();
        $this->json('POST', '/api/v1/lines', $lines);

        $this->assertApiResponse($lines);
    }

    /**
     * @test
     */
    public function testReadlines()
    {
        $lines = $this->makelines();
        $this->json('GET', '/api/v1/lines/'.$lines->id);

        $this->assertApiResponse($lines->toArray());
    }

    /**
     * @test
     */
    public function testUpdatelines()
    {
        $lines = $this->makelines();
        $editedlines = $this->fakelinesData();

        $this->json('PUT', '/api/v1/lines/'.$lines->id, $editedlines);

        $this->assertApiResponse($editedlines);
    }

    /**
     * @test
     */
    public function testDeletelines()
    {
        $lines = $this->makelines();
        $this->json('DELETE', '/api/v1/lines/'.$lines->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/lines/'.$lines->id);

        $this->assertResponseStatus(404);
    }
}
