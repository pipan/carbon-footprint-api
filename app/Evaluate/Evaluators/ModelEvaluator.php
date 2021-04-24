<?php

namespace App\Evaluate\Evaluators;

use App\Repository\ModelRepository;

class ModelEvaluator implements Evaluator
{
    private $modelRepository;
    private $evaluator;

    public function __construct(Evaluator $evaluator, ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
        $this->evaluator = $evaluator;
    }

    public function eval($schema, Context $context)
    {
        $model = $this->modelRepository->get($schema['id']);
        $modelInputs = [];
        foreach ($model['inputs'] as $input) {
            $modelInputs[$input['reference']] = $input['default_value'];
        }
        foreach ($schema['inputs'] as $reference => $inputSchemaRef) {
            $explode = explode(":", $inputSchemaRef);
            $inputSchema = $context->getReferenceSchema($explode[1]);
            if (isset($inputSchema['default']) && $inputSchema['default']) {
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