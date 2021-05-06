<?php

namespace App\Schema\Minify;

class ModelMinifier implements Minifier
{
    public function minify($model)
    {
        if (!isset($model['components'])) {
            return $model;
        }

        $minifier = new ChainMinifier([
            new InvalidInputCleanup($model['inputs']),
            new SchemaMinifier()
        ]);

        foreach ($model['components'] as &$component) {
            $component['schema'] = $minifier->minify($component['schema']);
        }
        return $model;
    }
}