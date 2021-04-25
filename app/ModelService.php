<?php

namespace App;

use App\Schema\Evaluate\Evaluators\Context;
use App\Schema\Evaluate\Evaluators\GeneralEvaluator;

class ModelService
{
    private $evaluator;

    public function __construct(GeneralEvaluator $evaluator)
    {
        $this->evaluator = $evaluator;
    }

    public function eval($model, $inputs = [])
    {
        $result = [
            'eval' => 0,
            'components' => []
        ];
        $evalInputs = [];
        foreach ($model['inputs'] as $input) {
            $evalInputs[$input['reference']] = $inputs[$input['reference']] ?? $input['default_value'];
        }

        foreach ($model['components'] as $component) {
            $result['components'][$component['id']] = $this->evaluator->eval(
                $component['schema']['root'],
                new Context($evalInputs, $component['schema'])
            );
            $result['eval'] += $result['components'][$component['id']];
        }
        
        
        return $result;
    }
}