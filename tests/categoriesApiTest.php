<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class categoriesApiTest extends TestCase
{
    use MakecategoriesTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatecategories()
    {
        $categories = $this->fakecategoriesData();
        $this->json('POST', '/api/v1/categories', $categories);

        $this->assertApiResponse($categories);
    }

    /**
     * @test
     */
    public function testReadcategories()
    {
        $categories = $this->makecategories();
        $this->json('GET', '/api/v1/categories/'.$categories->id);

        $this->assertApiResponse($categories->toArray());
    }

    /**
     * @test
     */
    public function testUpdatecategories()
    {
        $categories = $this->makecategories();
        $editedcategories = $this->fakecategoriesData();

        $this->json('PUT', '/api/v1/categories/'.$categories->id, $editedcategories);

        $this->assertApiResponse($editedcategories);
    }

    /**
     * @test
     */
    public function testDeletecategories()
    {
        $categories = $this->makecategories();
        $this->json('DELETE', '/api/v1/categories/'.$categories->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/categories/'.$categories->id);

        $this->assertResponseStatus(404);
    }
}
