<?php

namespace Tests\Feature\Model;

use App\Repository\ModelRepository;
use Tests\Mock\ModelRepositoryMock;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    private $modelRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->modelRepository = new ModelRepositoryMock();
        $this->modelRepository->withGet(1, [
            'id' => 1,
            'name' => 'test',
            'inputs' => [],
            'components' => []
        ]);

        $this->instance(ModelRepository::class, $this->modelRepository);
    }

    public function invalidRequests()
    {
        return [
            'empty' => [
                []
            ],
            'empty name' => [
                [
                    'name' => ''
                ]
            ],
            'empty description' => [
                [
                    'description' => ''
                ]
            ]
        ];
    }

    public function testResponse()
    {
        $response = $this->put('/api/model/1', [
            'name' => 'Test',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Test'
            ]);

        $updates = $this->modelRepository->getUpdates();
        $this->assertCount(1, $updates);
        $this->assertEquals(1, $updates[0]['id']);
        $this->assertEquals([
            'name' => 'Test'
        ], $updates[0]['model']);
    }

    public function testResponseNotFound()
    {
        $response = $this->put('/api/model/2', [
            'name' => 'Test',
        ]);

        $response->assertStatus(404)
            ->assertJson([]);

        $updates = $this->modelRepository->getUpdates();
        $this->assertCount(0, $updates);
    }

    /**
     * @dataProvider invalidRequests
     */
    public function testResponseInvalidData($payload)
    {
        $response = $this->put('/api/model/1', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Invalid request'
            ]);

        $updates = $this->modelRepository->getUpdates();
        $this->assertCount(0, $updates);
    }
}
