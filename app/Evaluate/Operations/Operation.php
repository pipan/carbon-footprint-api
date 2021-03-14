<?php

namespace App\Evaluate\Operations;

interface Operation
{
    public function apply($a, $b);
}