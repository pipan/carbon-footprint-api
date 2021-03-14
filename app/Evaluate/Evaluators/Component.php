<?php

namespace App\Evaluate\Evaluators;

use App\Repository\ModelRepository;

class Component implements Evaluator
{
    private $modelRepository;
    private $rootStack;

    public function __construct(ModelRepository $modelRepository, Evaluator $rootStack)
    {
        $this->modelRepository = $modelRepository;
        $this->rootStack = $rootStack;
    }

    public function eval($schema)
    {
        $model = $this->modelRepository->get($schema['id']);
        $modelInputs = [];
        foreach ($model['inputs'] as $input) {
            $modelInputs[$input['name']] = $input['default_value'];
        }
        foreach ($schema['inputs'] as $name => $inputSchema) {
            $modelInputs[$name] = $this->rootStack->eval($inputSchema);
        }

        $modelStack = new Any($this->modelRepository, $modelInputs);
        return $modelStack->eval($model['components']);
    }
}