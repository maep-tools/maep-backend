<?php

use App\Models\lines;
use App\Repositories\linesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class linesRepositoryTest extends TestCase
{
    use MakelinesTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var linesRepository
     */
    protected $linesRepo;

    public function setUp()
    {
        parent::setUp();
        $this->linesRepo = App::make(linesRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatelines()
    {
        $lines = $this->fakelinesData();
        $createdlines = $this->linesRepo->create($lines);
        $createdlines = $createdlines->toArray();
        $this->assertArrayHasKey('id', $createdlines);
        $this->assertNotNull($createdlines['id'], 'Created lines must have id specified');
        $this->assertNotNull(lines::find($createdlines['id']), 'lines with given id must be in DB');
        $this->assertModelData($lines, $createdlines);
    }

    /**
     * @test read
     */
    public function testReadlines()
    {
        $lines = $this->makelines();
        $dblines = $this->linesRepo->find($lines->id);
        $dblines = $dblines->toArray();
        $this->assertModelData($lines->toArray(), $dblines);
    }

    /**
     * @test update
     */
    public function testUpdatelines()
    {
        $lines = $this->makelines();
        $fakelines = $this->fakelinesData();
        $updatedlines = $this->linesRepo->update($fakelines, $lines->id);
        $this->assertModelData($fakelines, $updatedlines->toArray());
        $dblines = $this->linesRepo->find($lines->id);
        $this->assertModelData($fakelines, $dblines->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletelines()
    {
        $lines = $this->makelines();
        $resp = $this->linesRepo->delete($lines->id);
        $this->assertTrue($resp);
        $this->assertNull(lines::find($lines->id), 'lines should not exist in DB');
    }
}
