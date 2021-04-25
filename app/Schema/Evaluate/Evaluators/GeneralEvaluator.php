<?php

namespace App\Schema\Evaluate\Evaluators;

use App\Schema\Evaluate\Schema;
use App\Repository\ModelRepository;
use Exception;

class GeneralEvaluator implements Evaluator
{
    private $types;

    public function __construct(ModelRepository $modelRepository)
    {
        $this->types = [
            'constant' => new ConstantEvaluator(),
            'model' => new ModelEvaluator($this, $modelRepository),
            'stack' => new StackEvaluator($this),
            'model_input' => new StackEvaluator($this),
            'input' => new InputEvaluator(),
            'reference' => new ReferenceEvaluator($this)
        ];
    }

    public function eval($schema, Context $context)
    {
        $type = Schema::getType($schema);
        if (!isset($this->types[$type])) {
            throw new Exception("Unrecognized type: " . $type . " for schema: " . json_encode($schema));
        }
        $eval = $this->types[$type];
        return $eval->eval($schema, $context);
    }
}