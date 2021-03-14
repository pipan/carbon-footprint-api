<?php

namespace Tests\Unit;

use App\Evaluate\EvalService;
use PHPUnit\Framework\TestCase;
use Tests\Mock\ModelRepositoryMock;

class EvalComponentTest extends TestCase
{
    private $eval;
    private $modelRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->modelRepository = new ModelRepositoryMock();
        $this->modelRepository->withGet(1, [
            'id' => 1,
            'name' => '',
            'components' => [
                'type' => 'stack',
                'items' => [
                    'input:x',
                    '*',
                    'input:y'
                ]
            ],
            'inputs' => [
                [
                    'name' => 'x',
                    'default_value' => 10
                ],
                [
                    'name' => 'y',
                    'default_value' => 2
                ]
            ]
        ]);

        $this->modelRepository->withGet(2, [
            'id' => 2,
            'name' => '',
            'components' => [
                'type' => 'stack',
                'items' => [
                    'input:x',
                    '+',
                    'input:y'
                ]
            ],
            'inputs' => [
                [
                    'name' => 'x',
                    'default_value' => 5
                ],
                [
                    'name' => 'y',
                    'default_value' => 7
                ]
            ]
        ]);

        $this->eval = new EvalService($this->modelRepository);
    }

    public function testDefaultInputs()
    {
        $value = $this->eval->eval([
            'inputs' => [],
            'schema' => [
                'type' => 'stack',
                'items' => [
                    [
                        'type' => 'component',
                        'id' => 1,
                        'inputs' => []
                    ]
                ]
            ]
        ]);
        
        $this->assertEquals(20, $value);
    }

    public function testOverrideInputByConstatnt()
    {
        $value = $this->eval->eval([
            'inputs' => [],
            'schema' => [
                'type' => 'stack',
                'items' => [
                    [
                        'type' => 'component',
                        'id' => 1,
                        'inputs' => [
                            'x' => [
                                'type' => 'stack',
                                'items' => [
                                    'const:100'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        
        $this->assertEquals(200, $value);
    }

    public function testOverrideInputByInput()
    {
        $value = $this->eval->eval([
            'inputs' => [
                'x' => 100
            ],
            'schema' => [
                'type' => 'stack',
                'items' => [
                    [
                        'type' => 'component',
                        'id' => 1,
                        'inputs' => [
                            'x' => [
                                'type' => 'stack',
                                'items' => [
                                    'input:x'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        
        $this->assertEquals(200, $value);
    }

    public function testOverrideInputByStack()
    {
        $value = $this->eval->eval([
            'inputs' => [
                'x' => 100
            ],
            'schema' => [
                'type' => 'stack',
                'items' => [
                    [
                        'type' => 'component',
                        'id' => 1,
                        'inputs' => [
                            'x' => [
                                'type' => 'stack',
                                'items' => [
                                    'input:x',
                                    '+',
                                    'const:1'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        
        $this->assertEquals(202, $value);
    }

    public function testOverrideInputByComponent()
    {
        $value = $this->eval->eval([
            'inputs' => [],
            'schema' => [
                'type' => 'component',
                'id' => 1,
                'inputs' => [
                    'x' => [
                        'type' => 'component',
                        'id' => 2,
                        'inputs' => []
                    ]
                ]
            ]
        ]);
        
        $this->assertEquals(24, $value);
    }

    public function testOverrideInputByComponent2()
    {
        $value = $this->eval->eval([
            'inputs' => [
                'x' => 99
            ],
            'schema' => [
                'type' => 'component',
                'id' => 1,
                'inputs' => [
                    'x' => [
                        'type' => 'component',
                        'id' => 2,
                        'inputs' => []
                    ],
                    'y' => 'input:x'
                ]
            ]
        ]);
        
        $this->assertEquals(1188, $value);
    }

    public function testOverrideInputByComponent3()
    {
        $value = $this->eval->eval([
            'inputs' => [
                'x' => 99,
                'a' => 2,
                'b' => 3
            ],
            'schema' => [
                'type' => 'component',
                'id' => 1,
                'inputs' => [
                    'x' => [
                        'type' => 'component',
                        'id' => 2,
                        'inputs' => [
                            'x' => 'input:a',
                            'y' => [
                                'type' => 'stack',
                                'items' => [
                                    'const:1',
                                    '+',
                                    'input:b'
                                ]
                            ]
                        ]
                    ],
                    'y' => 'input:x'
                ]
            ]
        ]);
        
        $this->assertEquals(594, $value);
    }
}
