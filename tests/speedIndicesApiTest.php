<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class speedIndicesApiTest extends TestCase
{
    use MakespeedIndicesTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatespeedIndices()
    {
        $speedIndices = $this->fakespeedIndicesData();
        $this->json('POST', '/api/v1/speedIndices', $speedIndices);

        $this->assertApiResponse($speedIndices);
    }

    /**
     * @test
     */
    public function testReadspeedIndices()
    {
        $speedIndices = $this->makespeedIndices();
        $this->json('GET', '/api/v1/speedIndices/'.$speedIndices->id);

        $this->assertApiResponse($speedIndices->toArray());
    }

    /**
     * @test
     */
    public function testUpdatespeedIndices()
    {
        $speedIndices = $this->makespeedIndices();
        $editedspeedIndices = $this->fakespeedIndicesData();

        $this->json('PUT', '/api/v1/speedIndices/'.$speedIndices->id, $editedspeedIndices);

        $this->assertApiResponse($editedspeedIndices);
    }

    /**
     * @test
     */
    public function testDeletespeedIndices()
    {
        $speedIndices = $this->makespeedIndices();
        $this->json('DELETE', '/api/v1/speedIndices/'.$speedIndices->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/speedIndices/'.$speedIndices->id);

        $this->assertResponseStatus(404);
    }
}
