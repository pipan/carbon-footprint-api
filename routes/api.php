<?php

use App\Http\Controllers\ModelController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('/unit', [UnitController::class, 'index']);

Route::get('/search', SearchController::class);

Route::get('/model/{id}', [ModelController::class, 'get']);
Route::post('/model', [ModelController::class, 'create']);
Route::put('/model/{id}', [ModelController::class, 'update']);
