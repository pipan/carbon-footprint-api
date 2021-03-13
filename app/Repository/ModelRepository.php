<?php

namespace App\Repository;

interface ModelRepository
{
    public function get($id);
    public function search($query, $options);
    public function searchCount($query);
}