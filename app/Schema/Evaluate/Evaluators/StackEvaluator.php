<?php

namespace App\Schema\Evaluate\Evaluators;

use App\Schema\Evaluate\Schema;

class StackEvaluator implements Evaluator
{
    private $evaluator;
    private $operationEval;

    public function __construct(Evaluator $evaluator)
    {
        $this->evaluator = $evaluator;
        $this->operationEval = new OperationEvaluator();
    }

    public function eval($schema, Context $context)
    {
        $op = $this->operationEval->eval("+", $context);
        $result = 0;
        foreach ($schema['items'] as $item) {
            if (Schema::isOperation($item)) {
                $op = $this->operationEval->eval($item, $context);
                continue;
            }
            
            $result = $op->apply($result, $this->evaluator->eval($item, $context));
        }
        return $result;
    }
}