<?php

use App\Models\categories;
use App\Repositories\categoriesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class categoriesRepositoryTest extends TestCase
{
    use MakecategoriesTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var categoriesRepository
     */
    protected $categoriesRepo;

    public function setUp()
    {
        parent::setUp();
        $this->categoriesRepo = App::make(categoriesRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatecategories()
    {
        $categories = $this->fakecategoriesData();
        $createdcategories = $this->categoriesRepo->create($categories);
        $createdcategories = $createdcategories->toArray();
        $this->assertArrayHasKey('id', $createdcategories);
        $this->assertNotNull($createdcategories['id'], 'Created categories must have id specified');
        $this->assertNotNull(categories::find($createdcategories['id']), 'categories with given id must be in DB');
        $this->assertModelData($categories, $createdcategories);
    }

    /**
     * @test read
     */
    public function testReadcategories()
    {
        $categories = $this->makecategories();
        $dbcategories = $this->categoriesRepo->find($categories->id);
        $dbcategories = $dbcategories->toArray();
        $this->assertModelData($categories->toArray(), $dbcategories);
    }

    /**
     * @test update
     */
    public function testUpdatecategories()
    {
        $categories = $this->makecategories();
        $fakecategories = $this->fakecategoriesData();
        $updatedcategories = $this->categoriesRepo->update($fakecategories, $categories->id);
        $this->assertModelData($fakecategories, $updatedcategories->toArray());
        $dbcategories = $this->categoriesRepo->find($categories->id);
        $this->assertModelData($fakecategories, $dbcategories->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletecategories()
    {
        $categories = $this->makecategories();
        $resp = $this->categoriesRepo->delete($categories->id);
        $this->assertTrue($resp);
        $this->assertNull(categories::find($categories->id), 'categories should not exist in DB');
    }
}
