<?php

namespace App\Evaluate\Evaluators;

class ReferenceEvaluator implements Evaluator
{
    private $evaluator;

    public function __construct(Evaluator $evaluator)
    {
        $this->evaluator = $evaluator;
    }

    public function eval($schema, Context $context)
    {
        return $this->evaluator->eval($context->getReferenceSchema($this->getValue($schema)), $context);
    }

    private function getValue($schema)
    {
        if (is_array($schema)) {
            return $schema['value'];
        }
        $parts = explode(":", $schema);
        return $parts[1];
    }
}