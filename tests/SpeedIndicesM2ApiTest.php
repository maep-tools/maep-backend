<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SpeedIndicesM2ApiTest extends TestCase
{
    use MakeSpeedIndicesM2Trait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateSpeedIndicesM2()
    {
        $speedIndicesM2 = $this->fakeSpeedIndicesM2Data();
        $this->json('POST', '/api/v1/speedIndicesM2s', $speedIndicesM2);

        $this->assertApiResponse($speedIndicesM2);
    }

    /**
     * @test
     */
    public function testReadSpeedIndicesM2()
    {
        $speedIndicesM2 = $this->makeSpeedIndicesM2();
        $this->json('GET', '/api/v1/speedIndicesM2s/'.$speedIndicesM2->id);

        $this->assertApiResponse($speedIndicesM2->toArray());
    }

    /**
     * @test
     */
    public function testUpdateSpeedIndicesM2()
    {
        $speedIndicesM2 = $this->makeSpeedIndicesM2();
        $editedSpeedIndicesM2 = $this->fakeSpeedIndicesM2Data();

        $this->json('PUT', '/api/v1/speedIndicesM2s/'.$speedIndicesM2->id, $editedSpeedIndicesM2);

        $this->assertApiResponse($editedSpeedIndicesM2);
    }

    /**
     * @test
     */
    public function testDeleteSpeedIndicesM2()
    {
        $speedIndicesM2 = $this->makeSpeedIndicesM2();
        $this->json('DELETE', '/api/v1/speedIndicesM2s/'.$speedIndicesM2->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/speedIndicesM2s/'.$speedIndicesM2->id);

        $this->assertResponseStatus(404);
    }
}
