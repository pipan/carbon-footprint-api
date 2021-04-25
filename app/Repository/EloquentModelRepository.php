<?php

namespace App\Repository;

use App\Models\Model;

class EloquentModelRepository implements ModelRepository
{
    public function get($id)
    {
        $models = $this->getIn([$id]);
        if (count($models) === 0) {
            return null;
        }
        
        return $models[0];
    }

    public function getIn($ids)
    {
        $model = Model::with(['inputs', 'components', 'inputs.unit'])
            ->whereIn('id', $ids)
            ->get();
        if (!$model) {
            return [];
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