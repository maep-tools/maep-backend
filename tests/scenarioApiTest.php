<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class scenarioApiTest extends TestCase
{
    use MakescenarioTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatescenario()
    {
        $scenario = $this->fakescenarioData();
        $this->json('POST', '/api/v1/scenarios', $scenario);

        $this->assertApiResponse($scenario);
    }

    /**
     * @test
     */
    public function testReadscenario()
    {
        $scenario = $this->makescenario();
        $this->json('GET', '/api/v1/scenarios/'.$scenario->id);

        $this->assertApiResponse($scenario->toArray());
    }

    /**
     * @test
     */
    public function testUpdatescenario()
    {
        $scenario = $this->makescenario();
        $editedscenario = $this->fakescenarioData();

        $this->json('PUT', '/api/v1/scenarios/'.$scenario->id, $editedscenario);

        $this->assertApiResponse($editedscenario);
    }

    /**
     * @test
     */
    public function testDeletescenario()
    {
        $scenario = $this->makescenario();
        $this->json('DELETE', '/api/v1/scenarios/'.$scenario->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/scenarios/'.$scenario->id);

        $this->assertResponseStatus(404);
    }
}
