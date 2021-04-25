<?php

namespace App\Schema\Evaluate\Evaluators;

use App\Schema\Evaluate\Operations\AddOperation;
use App\Schema\Evaluate\Operations\DevideOperation;
use App\Schema\Evaluate\Operations\MultiplyOperation;

class OperationEvaluator implements Evaluator
{
    private $operations;

    public function __construct()
    {
        $this->operations = [
            '*' => new MultiplyOperation(),
            '+' => new AddOperation(),
            '/' => new DevideOperation()
        ];
    }

    public function eval($schema, Context $context)
    {
        $opIndex = $schema['value'] ?? $schema;
        return $this->operations[$opIndex];
    }
}