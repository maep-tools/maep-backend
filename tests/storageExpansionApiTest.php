<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class storageExpansionApiTest extends TestCase
{
    use MakestorageExpansionTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatestorageExpansion()
    {
        $storageExpansion = $this->fakestorageExpansionData();
        $this->json('POST', '/api/v1/storageExpansions', $storageExpansion);

        $this->assertApiResponse($storageExpansion);
    }

    /**
     * @test
     */
    public function testReadstorageExpansion()
    {
        $storageExpansion = $this->makestorageExpansion();
        $this->json('GET', '/api/v1/storageExpansions/'.$storageExpansion->id);

        $this->assertApiResponse($storageExpansion->toArray());
    }

    /**
     * @test
     */
    public function testUpdatestorageExpansion()
    {
        $storageExpansion = $this->makestorageExpansion();
        $editedstorageExpansion = $this->fakestorageExpansionData();

        $this->json('PUT', '/api/v1/storageExpansions/'.$storageExpansion->id, $editedstorageExpansion);

        $this->assertApiResponse($editedstorageExpansion);
    }

    /**
     * @test
     */
    public function testDeletestorageExpansion()
    {
        $storageExpansion = $this->makestorageExpansion();
        $this->json('DELETE', '/api/v1/storageExpansions/'.$storageExpansion->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/storageExpansions/'.$storageExpansion->id);

        $this->assertResponseStatus(404);
    }
}
