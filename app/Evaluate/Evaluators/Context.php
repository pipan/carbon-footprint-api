<?php

namespace App\Evaluate\Evaluators;

class Context
{
    private $inputs;
    private $references;

    public function __construct($inputs, $references)
    {
        $this->inputs = $inputs;
        $this->references = $references;
    }

    public function getInput($reference)
    {
        return $this->inputs[$reference];
    }

    public function getReferenceSchema($name)
    {
        return $this->references[$name];
    }
}