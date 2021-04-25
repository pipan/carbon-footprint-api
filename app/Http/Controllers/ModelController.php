<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\ModelService;
use App\Repository\ModelRepository;
use App\ResponseError;
use App\Schema\Enricher\SchemaEnricher;
use App\Schema\Minify\ModelMinifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModelController extends Controller
{
    private $modelRepository;
    private $modelService;
    private $modelMinifier;
    private $schemaEnricher;

    public function __construct(ModelRepository $modelRepository, ModelService $modelService, SchemaEnricher $schemaEnricher)
    {
        $this->modelRepository = $modelRepository;    
        $this->modelService = $modelService;
        $this->modelMinifier = new ModelMinifier();
        $this->schemaEnricher = $schemaEnricher;
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
            $component['schema'] = $this->schemaEnricher->enrich($component['schema']);
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
        $data = $request->all();
        $data = $this->modelMinifier->minify($data);
        
        $model = $this->modelRepository->insert($data);
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

        $data = $request->all();
        $data = $this->modelMinifier->minify($data);

        $model = $this->modelRepository->update($id, $data);
        return response($model);
    }
}