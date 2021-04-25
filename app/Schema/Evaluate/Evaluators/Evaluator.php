<?php

namespace App\Schema\Evaluate\Evaluators;

interface Evaluator
{
    public function eval($schema, Context $context);
}