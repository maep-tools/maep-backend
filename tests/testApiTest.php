<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class testApiTest extends TestCase
{
    use MaketestTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatetest()
    {
        $test = $this->faketestData();
        $this->json('POST', '/api/v1/tests', $test);

        $this->assertApiResponse($test);
    }

    /**
     * @test
     */
    public function testReadtest()
    {
        $test = $this->maketest();
        $this->json('GET', '/api/v1/tests/'.$test->id);

        $this->assertApiResponse($test->toArray());
    }

    /**
     * @test
     */
    public function testUpdatetest()
    {
        $test = $this->maketest();
        $editedtest = $this->faketestData();

        $this->json('PUT', '/api/v1/tests/'.$test->id, $editedtest);

        $this->assertApiResponse($editedtest);
    }

    /**
     * @test
     */
    public function testDeletetest()
    {
        $test = $this->maketest();
        $this->json('DELETE', '/api/v1/tests/'.$test->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/tests/'.$test->id);

        $this->assertResponseStatus(404);
    }
}
