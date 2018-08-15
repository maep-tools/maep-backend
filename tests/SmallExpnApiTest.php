<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SmallExpnApiTest extends TestCase
{
    use MakeSmallExpnTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateSmallExpn()
    {
        $smallExpn = $this->fakeSmallExpnData();
        $this->json('POST', '/api/v1/smallExpns', $smallExpn);

        $this->assertApiResponse($smallExpn);
    }

    /**
     * @test
     */
    public function testReadSmallExpn()
    {
        $smallExpn = $this->makeSmallExpn();
        $this->json('GET', '/api/v1/smallExpns/'.$smallExpn->id);

        $this->assertApiResponse($smallExpn->toArray());
    }

    /**
     * @test
     */
    public function testUpdateSmallExpn()
    {
        $smallExpn = $this->makeSmallExpn();
        $editedSmallExpn = $this->fakeSmallExpnData();

        $this->json('PUT', '/api/v1/smallExpns/'.$smallExpn->id, $editedSmallExpn);

        $this->assertApiResponse($editedSmallExpn);
    }

    /**
     * @test
     */
    public function testDeleteSmallExpn()
    {
        $smallExpn = $this->makeSmallExpn();
        $this->json('DELETE', '/api/v1/smallExpns/'.$smallExpn->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/smallExpns/'.$smallExpn->id);

        $this->assertResponseStatus(404);
    }
}
