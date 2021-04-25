<?php

namespace App\Schema\Minify;

class ModelMinifier implements Minifier
{
    private $schemaMinifier;

    public function __construct()
    {
        $this->schemaMinifier = new SchemaMinifier();
    }

    public function minify($model)
    {
        if (!isset($model['components'])) {
            return $model;
        }

        foreach ($model['components'] as &$component) {
            $component['schema'] = $this->schemaMinifier->minify($component['schema']);
        }
        return $model;
    }
}