<?php

namespace Tests\Feature\Model;

use App\Repository\ModelRepository;
use Tests\Mock\ModelRepositoryMock;
use Tests\TestCase;

class CreateTest extends TestCase
{
    private $modelRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->modelRepository = new ModelRepositoryMock();

        $this->instance(ModelRepository::class, $this->modelRepository);
    }

    public function invalidRequests()
    {
        return [
            'missing name' => [
                [
                    'description' => 'Some description',
                    'inputs' => [],
                    'components' => []
                ]
            ],
            'empty name' => [
                [
                    'name' => '',
                    'description' => 'Some description',
                    'inputs' => [],
                    'components' => []
                ]
            ],
            'missing description' => [
                [
                    'name' => 'Test',
                    'inputs' => [],
                    'components' => []
                ]
            ],
            'empty description' => [
                [
                    'name' => 'Test',
                    'description' => '',
                    'inputs' => [],
                    'components' => []
                ]
            ],
            'missing inputs' => [
                [
                    'name' => 'Test',
                    'description' => 'Some description',
                    'components' => []
                ]
            ],
            'missing components' => [
                [
                    'name' => 'Test',
                    'description' => 'Some description',
                    'inputs' => []
                ]
            ],
        ];
    }

    public function testResponse()
    {
        $response = $this->post('/api/model', [
            'name' => 'Test',
            'description' => 'Some description',
            'inputs' => [],
            'components' => []
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => 1,
                'name' => 'Test',
                'description' => 'Some description',
                'inputs' => [],
                'components' => []
            ]);

        $inserts = $this->modelRepository->getInserts();
        $this->assertCount(1, $inserts);
        $this->assertEquals([
            'name' => 'Test',
            'description' => 'Some description',
            'inputs' => [],
            'components' => []
        ], $inserts[0]);
    }

    /**
     * @dataProvider invalidRequests
     */
    public function testResponseInvalidData($payload)
    {
        $response = $this->post('/api/model', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Invalid request'
            ]);

        $inserts = $this->modelRepository->getInserts();
        $this->assertCount(0, $inserts);
    }

    public function testMinifyModelSchema()
    {
        $response = $this->post('/api/model', [
            'name' => 'Test',
            'description' => 'Some description',
            'inputs' => [],
            'components' => [
                [
                    'name' => 'test component',
                    'schema' => [
                        'root' => [
                            'type' => 'stack',
                            'items' => [
                                "reference:0011"
                            ]
                        ],
                        '0011' => [
                            'type' => 'model',
                            'id' => '1',
                            'inputs' => [],
                            'model' => 'something'
                        ]
                    ]
                ]
            ]
        ]);

        $response->assertStatus(200);

        $inserts = $this->modelRepository->getInserts();
        $this->assertEquals([
            'root' => [
                'type' => 'stack',
                'items' => [
                    "reference:0011"
                ]
            ],
            '0011' => [
                'type' => 'model',
                'id' => '1',
                'inputs' => []
            ]
        ], $inserts[0]['components'][0]['schema']);
    }

    public function testRemoveUnusedReferences()
    {
        $response = $this->post('/api/model', [
            'name' => 'Test',
            'description' => 'Some description',
            'inputs' => [],
            'components' => [
                [
                    'name' => 'test component',
                    'schema' => [
                        'root' => [
                            'type' => 'stack',
                            'items' => [
                                "reference:0011"
                            ]
                        ],
                        '0011' => [
                            'type' => 'model',
                            'id' => '1',
                            'inputs' => [],
                            'model' => 'something'
                        ],
                        '0012' => [
                            'type' => 'constant',
                            'value' => '2'
                        ],
                        '0013' => [
                            'type' => 'constant',
                            'value' => '23'
                        ]
                    ]
                ]
            ]
        ]);

        $response->assertStatus(200);

        $inserts = $this->modelRepository->getInserts();
        $this->assertEquals([
            'root' => [
                'type' => 'stack',
                'items' => [
                    "reference:0011"
                ]
            ],
            '0011' => [
                'type' => 'model',
                'id' => '1',
                'inputs' => []
            ]
        ], $inserts[0]['components'][0]['schema']);
    }

    public function testRemoveInvalidInputReferencesSingleInStack()
    {
        $response = $this->post('/api/model', [
            'name' => 'Test',
            'description' => 'Some description',
            'inputs' => [],
            'components' => [
                [
                    'name' => 'test component',
                    'schema' => [
                        'root' => [
                            'type' => 'stack',
                            'items' => [
                                "reference:0012"
                            ]
                        ],
                        '0011' => [
                            'type' => 'model',
                            'id' => '1',
                            'inputs' => [],
                            'model' => 'something'
                        ],
                        '0012' => [
                            'type' => 'input',
                            'reference' => 'inxx',
                            'parent' => 'root'
                        ]
                    ]
                ]
            ]
        ]);

        $response->assertStatus(200);

        $inserts = $this->modelRepository->getInserts();
        $this->assertEquals([
            'root' => [
                'type' => 'stack',
                'items' => []
            ]
        ], $inserts[0]['components'][0]['schema']);
    }

    public function testRemoveInvalidInputReferencesTwoInStack()
    {
        $response = $this->post('/api/model', [
            'name' => 'Test',
            'description' => 'Some description',
            'inputs' => [],
            'components' => [
                [
                    'name' => 'test component',
                    'schema' => [
                        'root' => [
                            'type' => 'stack',
                            'items' => [
                                "reference:0011",
                                "*",
                                "reference:0012"
                            ]
                        ],
                        '0011' => [
                            'type' => 'model',
                            'id' => '1',
                            'inputs' => []
                        ],
                        '0012' => [
                            'type' => 'input',
                            'reference' => 'inxx',
                            'parent' => 'root'
                        ]
                    ]
                ]
            ]
        ]);

        $response->assertStatus(200);

        $inserts = $this->modelRepository->getInserts();
        $this->assertEquals([
            'root' => [
                'type' => 'stack',
                'items' => [
                    "reference:0011"
                ]
            ],
            '0011' => [
                'type' => 'model',
                'id' => '1',
                'inputs' => []
            ]
        ], $inserts[0]['components'][0]['schema']);
    }

    public function testRemoveValidInputReferencesTwoInStack()
    {
        $response = $this->post('/api/model', [
            'name' => 'Test',
            'description' => 'Some description',
            'inputs' => [
                [
                    "id" => 1,
                    'reference' => 'inxx',
                    'default_value' => 100
                ]
            ],
            'components' => [
                [
                    'name' => 'test component',
                    'schema' => [
                        'root' => [
                            'type' => 'stack',
                            'items' => [
                                "reference:0011",
                                "*",
                                "reference:0012"
                            ]
                        ],
                        '0011' => [
                            'type' => 'model',
                            'id' => '1',
                            'inputs' => []
                        ],
                        '0012' => [
                            'type' => 'input',
                            'reference' => 'inxx',
                            'parent' => 'root'
                        ]
                    ]
                ]
            ]
        ]);

        $response->assertStatus(200);

        $inserts = $this->modelRepository->getInserts();
        $this->assertEquals([
            'root' => [
                'type' => 'stack',
                'items' => [
                    "reference:0011",
                    "*",
                    "reference:0012"
                ]
            ],
            '0011' => [
                'type' => 'model',
                'id' => '1',
                'inputs' => []
            ],
            '0012' => [
                'type' => 'input',
                'reference' => 'inxx',
                'parent' => 'root'
            ]
        ], $inserts[0]['components'][0]['schema']);
    }
}
