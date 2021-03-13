<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repository\ModelRepository;
use App\ResponseError;

class ModelController extends Controller
{
    private $modelRepository;

    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;    
    }

    public function get($id)
    {
        $model = $this->modelRepository->get($id);
        if (!$model) {
            return ResponseError::resourceNotFound();
        }
        return response($model);
    }
}