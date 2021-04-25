<?php

namespace App\Schema\Evaluate\Evaluators;

class ComponentEvaluator implements Evaluator
{
    private $evaluator;

    public function __construct(Evaluator $evaluator)
    {
        $this->evaluator = $evaluator;
    }

    public function eval($schema, Context $context)
    {
        $model = $this->modelRepository->get($schema['id']);
        $modelInputs = [];
        foreach ($model['inputs'] as $input) {
            $modelInputs[$input['reference']] = $input['default_value'];
        }
        foreach ($schema['inputs'] as $reference => $inputSchema) {
            if ($inputSchema['default']) {
                continue;
            }
            $modelInputs[$reference] = $this->evaluator->eval($inputSchema, $context);
        }

        $sum = 0;
        foreach ($model['components'] as $component) {
            $sum += $this->evaluator->eval($component['schema']['root'], new Context($modelInputs, $component['schema']));
        }
        return $sum;
    }
}