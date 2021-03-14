<?php

namespace App\Evaluate;

use App\Evaluate\Evaluators\Any;
use App\Repository\ModelRepository;

class EvalService
{
    private $modelRepository;

    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }

    public function eval($config)
    {
        $stack = new Any($this->modelRepository, $config['inputs']);
        return $stack->eval($config['schema']);
    }
}