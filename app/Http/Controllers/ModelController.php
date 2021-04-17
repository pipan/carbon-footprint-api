<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\ModelService;
use App\Repository\ModelRepository;
use App\ResponseError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            $input['value'] = $request->input($input['reference'], $input['default_value']);
        }
        return response($model);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'required'],
            'description' => ['bail', 'required'],
            'inputs' => ['bail', 'present', 'array'],
            'components' => ['bail', 'present', 'array']
        ]);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }
        
        $model = $this->modelRepository->insert($request->all());
        return response($model);
    }

    public function update($id, Request $request)
    {
        $data = $request->all();
        if (empty($data)) {
            return ResponseError::invalidRequest([
                'request' => 'Empty payload'
            ]);
        }
        $validator = Validator::make($data, [
            'name' => ['bail', 'nullable', 'filled'],
            'description' => ['bail', 'nullable', 'filled'],
            'inputs' => ['bail', 'nullable', 'array'],
            'components' => ['bail', 'nullable', 'array']
        ]);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $model = $this->modelRepository->get($id);
        if (!$model) {
            return ResponseError::resourceNotFound();
        }

        $model = $this->modelRepository->update($id, $request->all());
        return response($model);
    }
}