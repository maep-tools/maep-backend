<?php

use App\Models\type;
use App\Repositories\typeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class typeRepositoryTest extends TestCase
{
    use MaketypeTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var typeRepository
     */
    protected $typeRepo;

    public function setUp()
    {
        parent::setUp();
        $this->typeRepo = App::make(typeRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatetype()
    {
        $type = $this->faketypeData();
        $createdtype = $this->typeRepo->create($type);
        $createdtype = $createdtype->toArray();
        $this->assertArrayHasKey('id', $createdtype);
        $this->assertNotNull($createdtype['id'], 'Created type must have id specified');
        $this->assertNotNull(type::find($createdtype['id']), 'type with given id must be in DB');
        $this->assertModelData($type, $createdtype);
    }

    /**
     * @test read
     */
    public function testReadtype()
    {
        $type = $this->maketype();
        $dbtype = $this->typeRepo->find($type->id);
        $dbtype = $dbtype->toArray();
        $this->assertModelData($type->toArray(), $dbtype);
    }

    /**
     * @test update
     */
    public function testUpdatetype()
    {
        $type = $this->maketype();
        $faketype = $this->faketypeData();
        $updatedtype = $this->typeRepo->update($faketype, $type->id);
        $this->assertModelData($faketype, $updatedtype->toArray());
        $dbtype = $this->typeRepo->find($type->id);
        $this->assertModelData($faketype, $dbtype->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletetype()
    {
        $type = $this->maketype();
        $resp = $this->typeRepo->delete($type->id);
        $this->assertTrue($resp);
        $this->assertNull(type::find($type->id), 'type should not exist in DB');
    }
}
