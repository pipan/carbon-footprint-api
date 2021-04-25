<?php

namespace App\Schema\Evaluate\Evaluators;

class InputEvaluator implements Evaluator
{
    public function eval($schema, Context $context)
    {
        $name = $this->getName($schema);
        return $context->getInput($name);
    }

    private function getName($schema)
    {
        if (is_array($schema)) {
            return $schema['reference'];
        }
        $parts = explode(":", $schema);
        return $parts[1];
    }
}