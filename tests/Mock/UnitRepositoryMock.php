<?php

namespace Tests\Mock;

use App\Repository\UnitRepository;

class UnitRepositoryMock implements UnitRepository
{
    private $units = [];

    public function getAll()
    {
        return $this->units;
    }

    public function add($unit)
    {
        $this->units[] = $unit;
    }
}