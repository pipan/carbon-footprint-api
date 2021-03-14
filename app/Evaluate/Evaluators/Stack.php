<?php

namespace App\Evaluate\Evaluators;

use App\Evaluate\Schema;
use App\Repository\ModelRepository;

class Stack implements Evaluator
{
    private $any;
    private $operationEval;

    public function __construct(Evaluator $any)
    {
        $this->any = $any;
        $this->operationEval = new Operation();
    }

    public function eval($schema)
    {
        $op = $this->operationEval->eval("+");
        $result = 0;
        foreach ($schema['items'] as $item) {
            if (Schema::isOperation($item)) {
                $op = $this->operationEval->eval($item);
                continue;
            }
            
            $result = $op->apply($result, $this->any->eval($item));
        }
        return $result;
    }
}