<?php

namespace Tests\Unit;

use App\Evaluate\Evaluators\Context;
use App\Evaluate\Evaluators\GeneralEvaluator;
use PHPUnit\Framework\TestCase;
use Tests\Mock\ModelRepositoryMock;

class GeenralEvaluatorBasicTest extends TestCase
{
    private $eval;

    public function setUp(): void
    {
        parent::setUp();
        $this->eval = new GeneralEvaluator(new ModelRepositoryMock());
    }

    public function testConstant()
    {
        $value = $this->eval->eval([
            'type' => 'stack',
            'items' => [
                "constant:2.5"
            ]
        ], new Context([], []));
        
        $this->assertEquals(2.5, $value);
    }

    public function testConstantDirect()
    {
        $value = $this->eval->eval("constant:2.5", new Context([], []));
        
        $this->assertEquals(2.5, $value);
    }

    public function testInput()
    {
        $context = new Context([
            'test' => 400
        ], []);
        $value = $this->eval->eval([
            'type' => 'stack',
            'items' => [
                "input:test"
            ]
        ], $context);
        
        $this->assertEquals(400, $value);
    }

    public function testInputDirect()
    {
        $context = new Context([
            'test' => 400
        ], []);
        $value = $this->eval->eval("input:test", $context);
        
        $this->assertEquals(400, $value);
    }

    public function testInputArrayStyle()
    {
        $context = new Context([
            'test' => 400
        ], []);
        $value = $this->eval->eval([
            'type' => 'stack',
            'items' => [
                [
                    'type' => 'input',
                    'reference' => 'test'
                ]
            ]
        ], $context);
        
        $this->assertEquals(400, $value);
    }

    public function testMultiplyConstatnts()
    {
        $value = $this->eval->eval([
            'type' => 'stack',
            'items' => [
                "constant:2.5",
                "*",
                "constant:2"
            ]
            ], new Context([], []));
        
        $this->assertEquals(5, $value);
    }

    public function testAddConstatnts()
    {
        $value = $this->eval->eval([
            'type' => 'stack',
            'items' => [
                "constant:1",
                "+",
                "constant:2",
                "+",
                "constant:3"
            ]
            ], new Context([], []));
        
        $this->assertEquals(6, $value);
    }

    public function testCombination()
    {
        $context = new Context([
            'multiplier' => 3
        ], []);
        $value = $this->eval->eval([
            'type' => 'stack',
            'items' => [
                "input:multiplier",
                "*",
                "constant:2",
                "+",
                "constant:1"
            ]
            ], $context);
        
        $this->assertEquals(7, $value);
    }

    public function testMultipleStacks()
    {
        $context = new Context([
            'multiplier' => 3
        ], []);
        $value = $this->eval->eval([
            'type' => 'stack',
            'items' => [
                "input:multiplier",
                "*",
                [
                    'type' => 'stack',
                    'items' => [
                        "constant:2",
                        "+",
                        "constant:1"
                    ]
                ]
            ]
        ], $context);
        
        $this->assertEquals(9, $value);
    }

    public function testReference()
    {
        $context = new Context([
            'multiplier' => 3
        ], [
            'ref-1' => [
                'type' => 'stack',
                'items' => [
                    "constant:2",
                    "+",
                    "constant:1"
                ]
            ]
        ]);
        $value = $this->eval->eval([
            'type' => 'stack',
            'items' => [
                "input:multiplier",
                "*",
                "reference:ref-1"
            ]
        ], $context);
        
        $this->assertEquals(9, $value);
    }
}
