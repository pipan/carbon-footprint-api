<?php

namespace App\Evaluate\Operations;

class MultiplyOperation implements Operation
{
    public function apply($a, $b)
    {
        return $a * $b;
    }
}