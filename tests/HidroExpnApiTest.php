<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HidroExpnApiTest extends TestCase
{
    use MakeHidroExpnTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateHidroExpn()
    {
        $hidroExpn = $this->fakeHidroExpnData();
        $this->json('POST', '/api/v1/hidroExpns', $hidroExpn);

        $this->assertApiResponse($hidroExpn);
    }

    /**
     * @test
     */
    public function testReadHidroExpn()
    {
        $hidroExpn = $this->makeHidroExpn();
        $this->json('GET', '/api/v1/hidroExpns/'.$hidroExpn->id);

        $this->assertApiResponse($hidroExpn->toArray());
    }

    /**
     * @test
     */
    public function testUpdateHidroExpn()
    {
        $hidroExpn = $this->makeHidroExpn();
        $editedHidroExpn = $this->fakeHidroExpnData();

        $this->json('PUT', '/api/v1/hidroExpns/'.$hidroExpn->id, $editedHidroExpn);

        $this->assertApiResponse($editedHidroExpn);
    }

    /**
     * @test
     */
    public function testDeleteHidroExpn()
    {
        $hidroExpn = $this->makeHidroExpn();
        $this->json('DELETE', '/api/v1/hidroExpns/'.$hidroExpn->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/hidroExpns/'.$hidroExpn->id);

        $this->assertResponseStatus(404);
    }
}
