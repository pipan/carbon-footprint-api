<?php

namespace App\Evaluate\Evaluators;

interface Evaluator
{
    public function eval($schema, Context $context);
}