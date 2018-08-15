<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class typeApiTest extends TestCase
{
    use MaketypeTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatetype()
    {
        $type = $this->faketypeData();
        $this->json('POST', '/api/v1/types', $type);

        $this->assertApiResponse($type);
    }

    /**
     * @test
     */
    public function testReadtype()
    {
        $type = $this->maketype();
        $this->json('GET', '/api/v1/types/'.$type->id);

        $this->assertApiResponse($type->toArray());
    }

    /**
     * @test
     */
    public function testUpdatetype()
    {
        $type = $this->maketype();
        $editedtype = $this->faketypeData();

        $this->json('PUT', '/api/v1/types/'.$type->id, $editedtype);

        $this->assertApiResponse($editedtype);
    }

    /**
     * @test
     */
    public function testDeletetype()
    {
        $type = $this->maketype();
        $this->json('DELETE', '/api/v1/types/'.$type->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/types/'.$type->id);

        $this->assertResponseStatus(404);
    }
}
