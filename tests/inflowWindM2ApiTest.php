<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class inflowWindM2ApiTest extends TestCase
{
    use MakeinflowWindM2Trait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateinflowWindM2()
    {
        $inflowWindM2 = $this->fakeinflowWindM2Data();
        $this->json('POST', '/api/v1/inflowWindM2s', $inflowWindM2);

        $this->assertApiResponse($inflowWindM2);
    }

    /**
     * @test
     */
    public function testReadinflowWindM2()
    {
        $inflowWindM2 = $this->makeinflowWindM2();
        $this->json('GET', '/api/v1/inflowWindM2s/'.$inflowWindM2->id);

        $this->assertApiResponse($inflowWindM2->toArray());
    }

    /**
     * @test
     */
    public function testUpdateinflowWindM2()
    {
        $inflowWindM2 = $this->makeinflowWindM2();
        $editedinflowWindM2 = $this->fakeinflowWindM2Data();

        $this->json('PUT', '/api/v1/inflowWindM2s/'.$inflowWindM2->id, $editedinflowWindM2);

        $this->assertApiResponse($editedinflowWindM2);
    }

    /**
     * @test
     */
    public function testDeleteinflowWindM2()
    {
        $inflowWindM2 = $this->makeinflowWindM2();
        $this->json('DELETE', '/api/v1/inflowWindM2s/'.$inflowWindM2->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/inflowWindM2s/'.$inflowWindM2->id);

        $this->assertResponseStatus(404);
    }
}
