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
            'name' => 'test',
            'inputs' => [],
            'components' => []
        ]);

        $this->modelRepository->withGet(100, [
            'id' => 100,
            'name' => 'converter',
            'inputs' => [],
            'components' => [
                [
                    'id' => 100,
                    'name' => 'component',
                    'schema' => [
                        'root' => [
                            'type' => 'stack',
                            'items' => [
                                "constant:100"
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->modelRepository->withGet(101, [
            'id' => 101,
            'name' => 'with model',
            'inputs' => [],
            'components' => [
                [
                    'id' => 101,
                    'name' => 'component',
                    'schema' => [
                        'root' => [
                            'type' => 'stack',
                            'items' => [
                                'reference:0001'
                            ]
                        ],
                        '0001' => [
                            'type' => 'model',
                            'id' => 100,
                            'inputs' => []
                        ]
                    ]
                ]
            ]
        ]);

        $this->modelRepository->withGet(102, [
            'id' => 102,
            'name' => 'with model',
            'inputs' => [],
            'components' => [
                [
                    'id' => 102,
                    'name' => 'component',
                    'schema' => [
                        'root' => [
                            'type' => 'stack',
                            'items' => [
                                'reference:0001',
                                '+',
                                'reference:0002'
                            ]
                        ],
                        '0001' => [
                            'type' => 'model',
                            'id' => 100,
                            'inputs' => []
                        ],
                        '0002' => [
                            'type' => 'model',
                            'id' => 100,
                            'inputs' => []
                        ]
                    ]
                ]
            ]
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

    public function testEnrichModel()
    {
        $response = $this->get('/api/model/101');

        $response->assertJsonPath("components.0.schema.0001.model", [
            'name' => 'converter',
            'inputs' => []
        ]);
    }

    public function testEnrichModelTwice()
    {
        $response = $this->get('/api/model/102');

        $response->assertJsonPath("components.0.schema.0001.model", [
            'name' => 'converter',
            'inputs' => []
        ]);
        $response->assertJsonPath("components.0.schema.0002.model", [
            'name' => 'converter',
            'inputs' => []
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
