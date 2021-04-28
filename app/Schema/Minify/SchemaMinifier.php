<?php

namespace App\Schema\Minify;

use App\Schema\Evaluate\Schema;

class SchemaMinifier implements Minifier
{
    private $minifiers;
    private $defaultMinifier;
    private $refCleanup;

    public function __construct()
    {
        $this->defaultMinifier = new DummyMinifier();
        $this->refCleanup = new ReferenceCleanup();
        $this->minifiers = [
            'input' => new WhitelistMinifier(['type', 'parent', 'reference']),
            'constant' => new WhitelistMinifier(['type', 'parent', 'value', 'unit']),
            'stack' => new WhitelistMinifier(['type', 'parent', 'items']),
            'model' => new WhitelistMinifier(['type', 'parent', 'id', 'inputs']),
            'model_input' => new WhitelistMinifier(['type', 'parent', 'default', 'items']),
        ];
    }

    public function minify($schema)
    {
        $schema = $this->refCleanup->minify($schema);
        $result = [];
        foreach ($schema as $reference => $value) {
            $minifier = $this->getMinifier($value);
            $result[$reference] = $minifier->minify($value);
        }
        return $result;
    }

    private function getMinifier($schema)
    {
        $type = Schema::getType($schema);
        if (!isset($this->minifiers[$type])) {
            return $this->defaultMinifier;
        }
        return $this->minifiers[$type];
    }
}