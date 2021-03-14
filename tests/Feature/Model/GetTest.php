<?php

namespace Tests\Feature\Model;

use App\Repository\ModelRepository;
use Tests\Mock\ModelRepositoryMock;
use Tests\TestCase;

class GetTest extends TestCase
{
    private $modelRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->modelRepository = new ModelRepositoryMock();
        $this->modelRepository->withGet(1, [
            'id' => 1,
            'name' => 'test'
        ]);

        $this->instance(ModelRepository::class, $this->modelRepository);
    }

    public function testResponse()
    {
        $response = $this->get('/api/model/1');

        $response->assertStatus(200)
            ->assertJson([
                'id' => 1,
                'name' => 'test'
            ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->get('/api/model/2');

        $response->assertJson([
                'message' => 'Resource not found'
            ]);
    }
}
