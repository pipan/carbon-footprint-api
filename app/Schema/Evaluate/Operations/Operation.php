<?php

namespace App\Schema\Evaluate\Operations;

interface Operation
{
    public function apply($a, $b);
}