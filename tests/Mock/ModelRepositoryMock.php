<?php

namespace Tests\Mock;

use App\Repository\ModelRepository;

class ModelRepositoryMock implements ModelRepository
{
    private $getResult = [];
    private $searchResult = [];
    private $inserts = [];
    private $updates = [];
    private $incrementId = 1;

    public function withGet($id, $item)
    {
        $this->getResult[$id] = $item;
    }

    public function withSearch($query, $item)
    {
        $this->searchResult[$query] = $item;
    }

    public function getInserts()
    {
        return $this->inserts;
    }

    public function getUpdates()
    {
        return $this->updates;
    }

    public function get($id)
    {
        return $this->getResult[$id] ?? null;
    }

    public function getIn($ids)
    {
        $result = [];
        foreach ($ids as $id) {
            $result[] = $this->getResult[$id];
        }
        return $result;
    }

    public function search($query, $options)
    {
        return $this->searchResult[$query] ?? [];
    }

    public function searchCount($query, $options)
    {
        return count($this->search($query, $options));
    }

    public function insert($model)
    {
        $this->inserts[] = $model;
        $model['id'] = $this->incrementId;
        $this->incrementId++;
        return $model;
    }

    public function update($id, $model)
    {
        $this->updates[] = [
            'id' => $id,
            'model' => $model
        ];
        $model['id'] = $id;
        return $model;
    }
}