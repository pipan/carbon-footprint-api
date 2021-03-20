<?php

namespace App\Repository;

use App\Models\Model;

class EloquentModelRepository implements ModelRepository
{
    public function get($id)
    {
        $model = Model::with(['inputs', 'components', 'inputs.unit'])
            ->where('id', $id)
            ->first();
        if (!$model) {
            return null;
        }
        return $model->toArray();
    }

    public function search($query, $options)
    {
        $skip = 0;
        $limit = $options['limit'] ?? 12;
        if (isset($options['page'])) {
            $skip = $limit * max(0, $options['page'] - 1);
        }
        $models = Model::with(['inputs', 'components', 'inputs.unit'])
            ->where('name', 'like', "%" . $query . "%")
            ->where($options['filters'] ?? [])
            ->skip($skip)
            ->take($limit)
            ->get();
        return $models->toArray();
    }

    public function searchCount($query, $options)
    {
        return Model::where('name', 'like', "%" . $query . "%")
            ->where($options['filters'] ?? [])
            ->count();
    }

    public function insert($data)
    {
        $model = new Model($data);
        $model->save();
        $model->inputs()->createMany($data['inputs']);
        $model->components()->createMany($data['components']);
        return $model->toArray();
    }

    public function update($id, $data)
    {
        $model = Model::find($id);
        $model->fill($data);
        $model->save();

        $model->inputs()->delete();
        $model->inputs()->createMany($data['inputs']);

        $model->components()->delete();
        $model->components()->createMany($data['components']);

        return $model->toArray();
    }
}