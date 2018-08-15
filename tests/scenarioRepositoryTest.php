<?php

use App\Models\scenario;
use App\Repositories\scenarioRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class scenarioRepositoryTest extends TestCase
{
    use MakescenarioTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var scenarioRepository
     */
    protected $scenarioRepo;

    public function setUp()
    {
        parent::setUp();
        $this->scenarioRepo = App::make(scenarioRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatescenario()
    {
        $scenario = $this->fakescenarioData();
        $createdscenario = $this->scenarioRepo->create($scenario);
        $createdscenario = $createdscenario->toArray();
        $this->assertArrayHasKey('id', $createdscenario);
        $this->assertNotNull($createdscenario['id'], 'Created scenario must have id specified');
        $this->assertNotNull(scenario::find($createdscenario['id']), 'scenario with given id must be in DB');
        $this->assertModelData($scenario, $createdscenario);
    }

    /**
     * @test read
     */
    public function testReadscenario()
    {
        $scenario = $this->makescenario();
        $dbscenario = $this->scenarioRepo->find($scenario->id);
        $dbscenario = $dbscenario->toArray();
        $this->assertModelData($scenario->toArray(), $dbscenario);
    }

    /**
     * @test update
     */
    public function testUpdatescenario()
    {
        $scenario = $this->makescenario();
        $fakescenario = $this->fakescenarioData();
        $updatedscenario = $this->scenarioRepo->update($fakescenario, $scenario->id);
        $this->assertModelData($fakescenario, $updatedscenario->toArray());
        $dbscenario = $this->scenarioRepo->find($scenario->id);
        $this->assertModelData($fakescenario, $dbscenario->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletescenario()
    {
        $scenario = $this->makescenario();
        $resp = $this->scenarioRepo->delete($scenario->id);
        $this->assertTrue($resp);
        $this->assertNull(scenario::find($scenario->id), 'scenario should not exist in DB');
    }
}
