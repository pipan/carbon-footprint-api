<?php

namespace App\Schema\Evaluate\Evaluators;

class ConstantEvaluator implements Evaluator
{
    public function eval($schema, Context $context)
    {
        return floatval($this->getValue($schema));
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