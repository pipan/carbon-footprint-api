<?php

namespace App\Schema\Evaluate\Operations;

class MultiplyOperation implements Operation
{
    public function apply($a, $b)
    {
        return $a * $b;
    }
}