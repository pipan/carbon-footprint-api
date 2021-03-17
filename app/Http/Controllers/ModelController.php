<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\ModelService;
use App\Repository\ModelRepository;
use App\ResponseError;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    private $modelRepository;
    private $modelService;

    public function __construct(ModelRepository $modelRepository, ModelService $modelService)
    {
        $this->modelRepository = $modelRepository;    
        $this->modelService = $modelService;
    }

    public function get($id, Request $request)
    {
        $model = $this->modelRepository->get($id);
        if (!$model) {
            return ResponseError::resourceNotFound();
        }

        $result = $this->modelService->eval($model, $request->all());
        $model['eval'] = $result['eval'];
        foreach ($model['components'] as &$component) {
            $component['eval'] = $result['components'][$component['id']];
        }

        foreach ($model['inputs'] as &$input) {
            $input['value'] = $request->input($input['name'], $input['default_value']);
        }
        return response($model);
    }
}