<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Thermal_expnApiTest extends TestCase
{
    use MakeThermal_expnTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateThermal_expn()
    {
        $thermalExpn = $this->fakeThermal_expnData();
        $this->json('POST', '/api/v1/thermalExpns', $thermalExpn);

        $this->assertApiResponse($thermalExpn);
    }

    /**
     * @test
     */
    public function testReadThermal_expn()
    {
        $thermalExpn = $this->makeThermal_expn();
        $this->json('GET', '/api/v1/thermalExpns/'.$thermalExpn->id);

        $this->assertApiResponse($thermalExpn->toArray());
    }

    /**
     * @test
     */
    public function testUpdateThermal_expn()
    {
        $thermalExpn = $this->makeThermal_expn();
        $editedThermal_expn = $this->fakeThermal_expnData();

        $this->json('PUT', '/api/v1/thermalExpns/'.$thermalExpn->id, $editedThermal_expn);

        $this->assertApiResponse($editedThermal_expn);
    }

    /**
     * @test
     */
    public function testDeleteThermal_expn()
    {
        $thermalExpn = $this->makeThermal_expn();
        $this->json('DELETE', '/api/v1/thermalExpns/'.$thermalExpn->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/thermalExpns/'.$thermalExpn->id);

        $this->assertResponseStatus(404);
    }
}
