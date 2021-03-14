<?php

namespace Tests\Unit;

use App\Evaluate\EvalService;
use PHPUnit\Framework\TestCase;
use Tests\Mock\ModelRepositoryMock;

class EvalTest extends TestCase
{
    private $eval;

    public function setUp(): void
    {
        parent::setUp();
        $this->eval = new EvalService(new ModelRepositoryMock());
    }

    public function testConstant()
    {
        $value = $this->eval->eval([
            'inputs' => [],
            'schema' => [
                'type' => 'stack',
                'items' => [
                    "const:2.5"
                ]
            ]
        ]);
        
        $this->assertEquals(2.5, $value);
    }

    public function testConstantDirect()
    {
        $value = $this->eval->eval([
            'inputs' => [],
            'schema' => "const:2.5"
        ]);
        
        $this->assertEquals(2.5, $value);
    }

    public function testInput()
    {
        $value = $this->eval->eval([
            'inputs' => [
                'test' => 400
            ],
            'schema' => [
                'type' => 'stack',
                'items' => [
                    "input:test"
                ]
            ]
        ]);
        
        $this->assertEquals(400, $value);
    }

    public function testInputDirect()
    {
        $value = $this->eval->eval([
            'inputs' => [
                'test' => 400
            ],
            'schema' => "input:test"
        ]);
        
        $this->assertEquals(400, $value);
    }

    public function testMultiplyConstatnts()
    {
        $value = $this->eval->eval([
            'inputs' => [],
            'schema' => [
                'type' => 'stack',
                'items' => [
                    "const:2.5",
                    "*",
                    "const:2"
                ]
            ]
        ]);
        
        $this->assertEquals(5, $value);
    }

    public function testAddConstatnts()
    {
        $value = $this->eval->eval([
            'inputs' => [],
            'schema' => [
                'type' => 'stack',
                'items' => [
                    "const:1",
                    "+",
                    "const:2",
                    "+",
                    "const:3"
                ]
            ]
        ]);
        
        $this->assertEquals(6, $value);
    }

    public function testCombination()
    {
        $value = $this->eval->eval([
            'inputs' => [
                'multiplier' => 3
            ],
            'schema' => [
                'type' => 'stack',
                'items' => [
                    "input:multiplier",
                    "*",
                    "const:2",
                    "+",
                    "const:1"
                ]
            ]
        ]);
        
        $this->assertEquals(7, $value);
    }

    public function testMultipleStacks()
    {
        $value = $this->eval->eval([
            'inputs' => [
                'multiplier' => 3
            ],
            'schema' => [
                'type' => 'stack',
                'items' => [
                    "input:multiplier",
                    "*",
                    [
                        'type' => 'stack',
                        'items' => [
                            "const:2",
                            "+",
                            "const:1"
                        ]
                    ]
                ]
            ]
        ]);
        
        $this->assertEquals(9, $value);
    }
}
