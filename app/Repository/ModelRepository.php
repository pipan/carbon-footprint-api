<?php

namespace App\Repository;

interface ModelRepository
{
    public function get($id);
    public function getIn($ids);
    public function search($query, $options);
    public function searchCount($query, $options);

    public function insert($model);
    public function update($id, $model);
}