<?php

namespace App\Evaluate\Evaluators;

class Constant implements Evaluator
{
    public function eval($schema)
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