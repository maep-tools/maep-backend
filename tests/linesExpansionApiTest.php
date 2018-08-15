<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class linesExpansionApiTest extends TestCase
{
    use MakelinesExpansionTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatelinesExpansion()
    {
        $linesExpansion = $this->fakelinesExpansionData();
        $this->json('POST', '/api/v1/linesExpansions', $linesExpansion);

        $this->assertApiResponse($linesExpansion);
    }

    /**
     * @test
     */
    public function testReadlinesExpansion()
    {
        $linesExpansion = $this->makelinesExpansion();
        $this->json('GET', '/api/v1/linesExpansions/'.$linesExpansion->id);

        $this->assertApiResponse($linesExpansion->toArray());
    }

    /**
     * @test
     */
    public function testUpdatelinesExpansion()
    {
        $linesExpansion = $this->makelinesExpansion();
        $editedlinesExpansion = $this->fakelinesExpansionData();

        $this->json('PUT', '/api/v1/linesExpansions/'.$linesExpansion->id, $editedlinesExpansion);

        $this->assertApiResponse($editedlinesExpansion);
    }

    /**
     * @test
     */
    public function testDeletelinesExpansion()
    {
        $linesExpansion = $this->makelinesExpansion();
        $this->json('DELETE', '/api/v1/linesExpansions/'.$linesExpansion->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/linesExpansions/'.$linesExpansion->id);

        $this->assertResponseStatus(404);
    }
}
