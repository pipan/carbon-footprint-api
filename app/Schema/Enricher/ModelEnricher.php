<?php

namespace App\Schema\Enricher;

use App\Repository\ModelRepository;
use App\Schema\Evaluate\Schema;

class ModelEnricher implements Enricher
{
    private $modelRepository;

    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }

    public function enrich($schema)
    {
        $modelRefs = [];
        foreach ($schema as $reference => $value) {
            $type = Schema::getType($value);
            if ($type != 'model') {
                continue;
            }
            $modelRefs[$value['id']] = $reference;
        }
        
        $models = $this->modelRepository->getIn(array_keys($modelRefs));
        foreach ($models as $model) {
            $id = $model['id'];
            $reference = $modelRefs[$id];
            $schema[$reference]['model'] = [
                'name' => $model['name'],
                'inputs' => $model['inputs']
            ];
        }

        return $schema;
    }
}