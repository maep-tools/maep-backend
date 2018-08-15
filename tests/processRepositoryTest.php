<?php

use App\Models\process;
use App\Repositories\processRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class processRepositoryTest extends TestCase
{
    use MakeprocessTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var processRepository
     */
    protected $processRepo;

    public function setUp()
    {
        parent::setUp();
        $this->processRepo = App::make(processRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateprocess()
    {
        $process = $this->fakeprocessData();
        $createdprocess = $this->processRepo->create($process);
        $createdprocess = $createdprocess->toArray();
        $this->assertArrayHasKey('id', $createdprocess);
        $this->assertNotNull($createdprocess['id'], 'Created process must have id specified');
        $this->assertNotNull(process::find($createdprocess['id']), 'process with given id must be in DB');
        $this->assertModelData($process, $createdprocess);
    }

    /**
     * @test read
     */
    public function testReadprocess()
    {
        $process = $this->makeprocess();
        $dbprocess = $this->processRepo->find($process->id);
        $dbprocess = $dbprocess->toArray();
        $this->assertModelData($process->toArray(), $dbprocess);
    }

    /**
     * @test update
     */
    public function testUpdateprocess()
    {
        $process = $this->makeprocess();
        $fakeprocess = $this->fakeprocessData();
        $updatedprocess = $this->processRepo->update($fakeprocess, $process->id);
        $this->assertModelData($fakeprocess, $updatedprocess->toArray());
        $dbprocess = $this->processRepo->find($process->id);
        $this->assertModelData($fakeprocess, $dbprocess->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteprocess()
    {
        $process = $this->makeprocess();
        $resp = $this->processRepo->delete($process->id);
        $this->assertTrue($resp);
        $this->assertNull(process::find($process->id), 'process should not exist in DB');
    }
}
