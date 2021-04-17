<?php

namespace App\Evaluate\Operations;

class DevideOperation implements Operation
{
    public function apply($a, $b)
    {
        return $a / $b;
    }
}