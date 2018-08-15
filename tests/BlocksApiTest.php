<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BlocksApiTest extends TestCase
{
    use MakeBlocksTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateBlocks()
    {
        $blocks = $this->fakeBlocksData();
        $this->json('POST', '/api/v1/blocks', $blocks);

        $this->assertApiResponse($blocks);
    }

    /**
     * @test
     */
    public function testReadBlocks()
    {
        $blocks = $this->makeBlocks();
        $this->json('GET', '/api/v1/blocks/'.$blocks->id);

        $this->assertApiResponse($blocks->toArray());
    }

    /**
     * @test
     */
    public function testUpdateBlocks()
    {
        $blocks = $this->makeBlocks();
        $editedBlocks = $this->fakeBlocksData();

        $this->json('PUT', '/api/v1/blocks/'.$blocks->id, $editedBlocks);

        $this->assertApiResponse($editedBlocks);
    }

    /**
     * @test
     */
    public function testDeleteBlocks()
    {
        $blocks = $this->makeBlocks();
        $this->json('DELETE', '/api/v1/blocks/'.$blocks->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/blocks/'.$blocks->id);

        $this->assertResponseStatus(404);
    }
}
