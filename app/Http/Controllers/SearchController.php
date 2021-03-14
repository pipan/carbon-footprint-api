<?php

namespace App\Http\Controllers;

use App\Evaluate\EvalService;
use App\Http\Controllers\Controller;
use App\Repository\ModelRepository;
use App\ResponseError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    private $modelRepository;
    private $evalService;

    public function __construct(ModelRepository $modelRepository, EvalService $evalService)
    {
        $this->modelRepository = $modelRepository;
        $this->evalService = $evalService;
    }

    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => ['bail', 'required'],
        ]);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $filters = [];
        if ($request->input('output')) {
            $filters[] = [
                'output_unit_id',
                '=',
                $request->input('output')
            ];
        }
        $options = [
            'limit' => intval($request->input('limit', 12)),
            'page' => intval($request->input('page', 1)),
            'filters' => $filters
        ];

        $models = [];
        $total = $this->modelRepository->searchCount($request->input('query'));
        if ($total > 0) {
            $models = $this->modelRepository->search($request->input('query'), $options);
        }

        foreach ($models as &$model) {
            $evalInputs = [];
            foreach ($model['inputs'] as $input) {
                $evalInputs[$input['name']] = $input['default_value'];
            }
            $model['carbon'] = $this->evalService->eval([
                'inputs' => $evalInputs,
                'schema' => $model['components']
            ]);
        }
        
        return response([
            'page' => $options['page'],
            'limit' => $options['limit'],
            'total' => $total,
            'items' => $models
        ]);
    }
}