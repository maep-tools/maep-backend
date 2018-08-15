<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WPowCurveM2ApiTest extends TestCase
{
    use MakeWPowCurveM2Trait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateWPowCurveM2()
    {
        $wPowCurveM2 = $this->fakeWPowCurveM2Data();
        $this->json('POST', '/api/v1/wPowCurveM2s', $wPowCurveM2);

        $this->assertApiResponse($wPowCurveM2);
    }

    /**
     * @test
     */
    public function testReadWPowCurveM2()
    {
        $wPowCurveM2 = $this->makeWPowCurveM2();
        $this->json('GET', '/api/v1/wPowCurveM2s/'.$wPowCurveM2->id);

        $this->assertApiResponse($wPowCurveM2->toArray());
    }

    /**
     * @test
     */
    public function testUpdateWPowCurveM2()
    {
        $wPowCurveM2 = $this->makeWPowCurveM2();
        $editedWPowCurveM2 = $this->fakeWPowCurveM2Data();

        $this->json('PUT', '/api/v1/wPowCurveM2s/'.$wPowCurveM2->id, $editedWPowCurveM2);

        $this->assertApiResponse($editedWPowCurveM2);
    }

    /**
     * @test
     */
    public function testDeleteWPowCurveM2()
    {
        $wPowCurveM2 = $this->makeWPowCurveM2();
        $this->json('DELETE', '/api/v1/wPowCurveM2s/'.$wPowCurveM2->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/wPowCurveM2s/'.$wPowCurveM2->id);

        $this->assertResponseStatus(404);
    }
}
