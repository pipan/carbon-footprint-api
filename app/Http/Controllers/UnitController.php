<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repository\UnitRepository;

class UnitController extends Controller
{
    private $unitRepository;

    public function __construct(UnitRepository $unitRepository)
    {
        $this->unitRepository = $unitRepository;    
    }

    public function index()
    {
        $units = $this->unitRepository->getAll();
        return response($units);
    }
}