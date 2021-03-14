<?php

namespace App\Evaluate\Evaluators;

use App\Evaluate\Schema;
use App\Repository\ModelRepository;

class Any implements Evaluator
{
    private $types;

    public function __construct(ModelRepository $modelRepository, $inputs = [])
    {
        $this->types = [
            'const' => new Constant(),
            'component' => new Component($modelRepository, $this),
            'stack' => new Stack($this),
            'input' => new Input($inputs)
        ];
    }

    public function eval($schema)
    {
        $type = Schema::getType($schema);
        $eval = $this->types[$type];
        return $eval->eval($schema);
    }
}