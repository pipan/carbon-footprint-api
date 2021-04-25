<?php

namespace App\Schema\Evaluate\Evaluators;

use App\Schema\Evaluate\Schema;

class Context
{
    private $inputs;
    private $references;

    public function __construct($inputs, $references)
    {
        $this->inputs = $inputs;
        $this->references = $references;
    }

    public function getInput($reference)
    {
        return $this->inputs[$reference];
    }

    public function getReferenceSchema($name)
    {
        return $this->references[$name];
    }

    public function getSchemaFollowReference($schema)
    {
        $type = Schema::getType($schema);
        if ($type !== 'reference') {
            return $schema;
        }

        if (is_array($schema)) {
            return $this->getReferenceSchema($schema['value']);
        }
        $parts = explode(":", $schema);
        return $this->getReferenceSchema($parts[1]);
    }
}