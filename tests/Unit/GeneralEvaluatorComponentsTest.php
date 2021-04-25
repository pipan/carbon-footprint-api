<?php

namespace Tests\Unit;

use App\Schema\Evaluate\Evaluators\Context;
use App\Schema\Evaluate\Evaluators\GeneralEvaluator;
use PHPUnit\Framework\TestCase;
use Tests\Mock\ModelRepositoryMock;

class GeenralEvaluatorComponentsTest extends TestCase
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
                [
                    'id' => 1,
                    'name' => '',
                    'schema' => [
                        'root' => [
                            'type' => 'stack',
                            'items' => [
                                'input:i-1',
                                '*',
                                'input:i-2'
                            ]
                        ]
                    ]  
                ]
            ],
            'inputs' => [
                [
                    'reference' => 'i-1',
                    'name' => 'x',
                    'default_value' => 10
                ],
                [
                    'reference' => 'i-2',
                    'name' => 'y',
                    'default_value' => 2
                ]
            ]
        ]);

        $this->modelRepository->withGet(2, [
            'id' => 2,
            'name' => '',
            'components' => [
                [
                    'id' => 1,
                    'name' => '',
                    'schema' => [
                        'root' => [
                            'type' => 'stack',
                            'items' => [
                                'input:i-1',
                                '+',
                                'input:i-2'
                            ]
                        ]
                    ]
                ]
            ],
            'inputs' => [
                [
                    'reference' => 'i-1',
                    'name' => 'x',
                    'default_value' => 5
                ],
                [
                    'reference' => 'i-2',
                    'name' => 'y',
                    'default_value' => 7
                ]
            ]
        ]);

        $this->eval = new GeneralEvaluator($this->modelRepository);
    }

    public function testDefaultInputs()
    {
        $context = new Context([], []);
        $result = $this->eval->eval([
            'type' => 'model',
            'id' => 1,
            'inputs' => []
        ], $context);
        
        $this->assertEquals(20, $result);
    }

    public function testOverrideInputByConstatnt()
    {
        $context = new Context([], []);
        $value = $this->eval->eval([
            'type' => 'stack',
            'items' => [
                [
                    'type' => 'model',
                    'id' => 1,
                    'inputs' => [
                        'i-1' => [
                            'default' => false,
                            'type' => 'stack',
                            'items' => [
                                'constant:100'
                            ]
                        ]
                    ]
                ]
            ]
        ], $context);
        
        $this->assertEquals(200, $value);
    }

    public function testOverrideInputByInput()
    {
        $context = new Context([
            'override-i1' => 100
        ], []);
        $value = $this->eval->eval([
            'type' => 'model',
            'id' => 1,
            'inputs' => [
                'i-1' => [
                    'type' => 'stack',
                    'items' => [
                        'input:override-i1'
                    ]
                ]
            ]
        ], $context);
        
        $this->assertEquals(200, $value);
    }

    public function testOverrideInputByStack()
    {
        $context = new Context([
            'override-i1' => 100
        ], []);
        $value = $this->eval->eval([
            'type' => 'model',
            'id' => 1,
            'inputs' => [
                'i-1' => [
                    'type' => 'stack',
                    'items' => [
                        'input:override-i1',
                        '+',
                        'constant:1'
                    ]
                ]
            ]
        ], $context);
        
        $this->assertEquals(202, $value);
    }

    public function testOverrideInputByComponent()
    {
        $context = new Context([], []);
        $value = $this->eval->eval([
            'type' => 'model',
            'id' => 1,
            'inputs' => [
                'i-1' => [
                    'type' => 'model',
                    'id' => 2,
                    'inputs' => []
                ]
            ]
        ], $context);
        
        $this->assertEquals(24, $value);
    }

    public function testOverrideInputByComponent2()
    {
        $context = new Context([
            'override-i1' => 99
        ], []);
        $value = $this->eval->eval([
            'type' => 'model',
            'id' => 1,
            'inputs' => [
                'i-1' => [
                    'type' => 'model',
                    'id' => 2,
                    'inputs' => []
                ],
                'i-2' => 'input:override-i1'
            ]
            ], $context);
        
        $this->assertEquals(1188, $value);
    }

    public function testOverrideInputByComponent3()
    {
        $context = new Context([
            'x' => 99,
            'a' => 2,
            'b' => 3
        ], []);
        $value = $this->eval->eval([
            'type' => 'model',
            'id' => 1,
            'inputs' => [
                'i-1' => [
                    'type' => 'model',
                    'id' => 2,
                    'inputs' => [
                        'i-1' => 'input:a',
                        'i-2' => [
                            'type' => 'stack',
                            'items' => [
                                'constant:1',
                                '+',
                                'input:b'
                            ]
                        ]
                    ]
                ],
                'i-2' => 'input:x'
            ]
        ], $context);
        
        $this->assertEquals(594, $value);
    }
}
