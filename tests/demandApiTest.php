<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class demandApiTest extends TestCase
{
    use MakedemandTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatedemand()
    {
        $demand = $this->fakedemandData();
        $this->json('POST', '/api/v1/demands', $demand);

        $this->assertApiResponse($demand);
    }

    /**
     * @test
     */
    public function testReaddemand()
    {
        $demand = $this->makedemand();
        $this->json('GET', '/api/v1/demands/'.$demand->id);

        $this->assertApiResponse($demand->toArray());
    }

    /**
     * @test
     */
    public function testUpdatedemand()
    {
        $demand = $this->makedemand();
        $editeddemand = $this->fakedemandData();

        $this->json('PUT', '/api/v1/demands/'.$demand->id, $editeddemand);

        $this->assertApiResponse($editeddemand);
    }

    /**
     * @test
     */
    public function testDeletedemand()
    {
        $demand = $this->makedemand();
        $this->json('DELETE', '/api/v1/demands/'.$demand->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/demands/'.$demand->id);

        $this->assertResponseStatus(404);
    }
}
