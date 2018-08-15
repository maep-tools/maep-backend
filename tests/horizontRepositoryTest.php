<?php

use App\Models\horizont;
use App\Repositories\horizontRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class horizontRepositoryTest extends TestCase
{
    use MakehorizontTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var horizontRepository
     */
    protected $horizontRepo;

    public function setUp()
    {
        parent::setUp();
        $this->horizontRepo = App::make(horizontRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatehorizont()
    {
        $horizont = $this->fakehorizontData();
        $createdhorizont = $this->horizontRepo->create($horizont);
        $createdhorizont = $createdhorizont->toArray();
        $this->assertArrayHasKey('id', $createdhorizont);
        $this->assertNotNull($createdhorizont['id'], 'Created horizont must have id specified');
        $this->assertNotNull(horizont::find($createdhorizont['id']), 'horizont with given id must be in DB');
        $this->assertModelData($horizont, $createdhorizont);
    }

    /**
     * @test read
     */
    public function testReadhorizont()
    {
        $horizont = $this->makehorizont();
        $dbhorizont = $this->horizontRepo->find($horizont->id);
        $dbhorizont = $dbhorizont->toArray();
        $this->assertModelData($horizont->toArray(), $dbhorizont);
    }

    /**
     * @test update
     */
    public function testUpdatehorizont()
    {
        $horizont = $this->makehorizont();
        $fakehorizont = $this->fakehorizontData();
        $updatedhorizont = $this->horizontRepo->update($fakehorizont, $horizont->id);
        $this->assertModelData($fakehorizont, $updatedhorizont->toArray());
        $dbhorizont = $this->horizontRepo->find($horizont->id);
        $this->assertModelData($fakehorizont, $dbhorizont->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletehorizont()
    {
        $horizont = $this->makehorizont();
        $resp = $this->horizontRepo->delete($horizont->id);
        $this->assertTrue($resp);
        $this->assertNull(horizont::find($horizont->id), 'horizont should not exist in DB');
    }
}
