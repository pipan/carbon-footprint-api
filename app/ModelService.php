<?php

namespace App;

use App\Evaluate\EvalService;
use App\Evaluate\Evaluators\Any;

class ModelService
{
    private $evalService;

    public function __construct(EvalService $evalService)
    {
        $this->evalService = $evalService;
    }

    public function eval($model, $inputs = [])
    {
        $result = [
            'eval' => 0,
            'components' => []
        ];
        $evalInputs = [];
        foreach ($model['inputs'] as $input) {
            $evalInputs[$input['name']] = $inputs[$input['name']] ?? $input['default_value'];
        }

        foreach ($model['components'] as $component) {
            $result['components'][$component['id']] = $this->evalService->eval([
                'inputs' => $evalInputs,
                'schema' => $component['schema']
            ]);
            $result['eval'] += $result['components'][$component['id']];
        }
        
        return $result;
    }
}