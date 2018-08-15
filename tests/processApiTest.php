<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class processApiTest extends TestCase
{
    use MakeprocessTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateprocess()
    {
        $process = $this->fakeprocessData();
        $this->json('POST', '/api/v1/processes', $process);

        $this->assertApiResponse($process);
    }

    /**
     * @test
     */
    public function testReadprocess()
    {
        $process = $this->makeprocess();
        $this->json('GET', '/api/v1/processes/'.$process->id);

        $this->assertApiResponse($process->toArray());
    }

    /**
     * @test
     */
    public function testUpdateprocess()
    {
        $process = $this->makeprocess();
        $editedprocess = $this->fakeprocessData();

        $this->json('PUT', '/api/v1/processes/'.$process->id, $editedprocess);

        $this->assertApiResponse($editedprocess);
    }

    /**
     * @test
     */
    public function testDeleteprocess()
    {
        $process = $this->makeprocess();
        $this->json('DELETE', '/api/v1/processes/'.$process->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/processes/'.$process->id);

        $this->assertResponseStatus(404);
    }
}
