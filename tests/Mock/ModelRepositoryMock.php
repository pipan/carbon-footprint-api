<?php

namespace Tests\Mock;

use App\Repository\ModelRepository;

class ModelRepositoryMock implements ModelRepository
{
    private $getResult = [];
    private $searchResult = [];

    public function withGet($id, $item)
    {
        $this->getResult[$id] = $item;
    }

    public function withSearch($query, $item)
    {
        $this->searchResult[$query] = $item;
    }

    public function get($id)
    {
        return $this->getResult[$id] ?? null;
    }

    public function search($query, $options)
    {
        return $this->searchResult[$query] ?? [];
    }

    public function searchCount($query)
    {
        return count($this->search($query, []));
    }
}