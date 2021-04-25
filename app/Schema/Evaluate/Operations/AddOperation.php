<?php

namespace App\Schema\Evaluate\Operations;

class AddOperation implements Operation
{
    public function apply($a, $b)
    {
        return $a + $b;
    }
}