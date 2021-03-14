<?php

namespace App\Evaluate\Evaluators;

use App\Evaluate\Operations\AddOperation;
use App\Evaluate\Operations\MultiplyOperation;

class Operation implements Evaluator
{
    private $operations;

    public function __construct()
    {
        $this->operations = [
            '*' => new MultiplyOperation(),
            '+' => new AddOperation()
        ];
    }

    public function eval($schema)
    {
        $opIndex = $schema['value'] ?? $schema;
        return $this->operations[$opIndex];
    }
}