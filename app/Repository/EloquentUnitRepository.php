<?php

namespace App\Repository;

use App\Models\Unit;

class EloquentUnitRepository implements UnitRepository
{
    public function getAll()
    {
        $units = Unit::with(['scales'])->get();
        return $units->toArray();
    }
}