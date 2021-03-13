<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repository\ModelRepository;
use App\ResponseError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    private $modelRepository;

    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;    
    }

    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => ['bail', 'required'],
        ]);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $options = [
            'limit' => intval($request->input('limit', 12)),
            'page' => intval($request->input('page', 1))
        ];

        $models = [];
        $total = $this->modelRepository->searchCount($request->input('query'));
        if ($total > 0) {
            $models = $this->modelRepository->search($request->input('query'), $options);
        }
        
        return response([
            'page' => $options['page'],
            'limit' => $options['limit'],
            'total' => $total,
            'items' => $models
        ]);
    }
}