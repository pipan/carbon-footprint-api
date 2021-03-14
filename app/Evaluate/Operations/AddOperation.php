<?php

namespace App\Evaluate\Operations;

class AddOperation implements Operation
{
    public function apply($a, $b)
    {
        return $a + $b;
    }
}