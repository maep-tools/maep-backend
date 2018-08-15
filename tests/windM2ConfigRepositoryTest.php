<?php

use App\Models\WindM2Config;
use App\Repositories\WindM2ConfigRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WindM2ConfigRepositoryTest extends TestCase
{
    use MakeWindM2ConfigTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var WindM2ConfigRepository
     */
    protected $windM2ConfigRepo;

    public function setUp()
    {
        parent::setUp();
        $this->windM2ConfigRepo = App::make(WindM2ConfigRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateWindM2Config()
    {
        $windM2Config = $this->fakeWindM2ConfigData();
        $createdWindM2Config = $this->windM2ConfigRepo->create($windM2Config);
        $createdWindM2Config = $createdWindM2Config->toArray();
        $this->assertArrayHasKey('id', $createdWindM2Config);
        $this->assertNotNull($createdWindM2Config['id'], 'Created WindM2Config must have id specified');
        $this->assertNotNull(WindM2Config::find($createdWindM2Config['id']), 'WindM2Config with given id must be in DB');
        $this->assertModelData($windM2Config, $createdWindM2Config);
    }

    /**
     * @test read
     */
    public function testReadWindM2Config()
    {
        $windM2Config = $this->makeWindM2Config();
        $dbWindM2Config = $this->windM2ConfigRepo->find($windM2Config->id);
        $dbWindM2Config = $dbWindM2Config->toArray();
        $this->assertModelData($windM2Config->toArray(), $dbWindM2Config);
    }

    /**
     * @test update
     */
    public function testUpdateWindM2Config()
    {
        $windM2Config = $this->makeWindM2Config();
        $fakeWindM2Config = $this->fakeWindM2ConfigData();
        $updatedWindM2Config = $this->windM2ConfigRepo->update($fakeWindM2Config, $windM2Config->id);
        $this->assertModelData($fakeWindM2Config, $updatedWindM2Config->toArray());
        $dbWindM2Config = $this->windM2ConfigRepo->find($windM2Config->id);
        $this->assertModelData($fakeWindM2Config, $dbWindM2Config->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteWindM2Config()
    {
        $windM2Config = $this->makeWindM2Config();
        $resp = $this->windM2ConfigRepo->delete($windM2Config->id);
        $this->assertTrue($resp);
        $this->assertNull(WindM2Config::find($windM2Config->id), 'WindM2Config should not exist in DB');
    }
}
