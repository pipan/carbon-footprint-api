<?php

namespace App\Evaluate\Evaluators;

class Input implements Evaluator
{
    private $inputs;

    public function __construct($inputs)
    {
        $this->inputs = $inputs;
    }

    public function eval($schema)
    {
        $name = $this->getName($schema);
        return $this->inputs[$name];
    }

    private function getName($schema)
    {
        if (is_array($schema)) {
            return $schema['name'];
        }
        $parts = explode(":", $schema);
        return $parts[1];
    }
}