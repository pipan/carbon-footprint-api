<?php

namespace App\Schema\Minify;

use App\Schema\Evaluate\Schema;
use Iterator;

class ReferenceBreadthFirstIterator implements Iterator
{
    private $schema;
    private $stack;
    private $position;
    private $extractors;

    public function __construct($schema)
    {
        $this->schema = $schema;
        $this->extractors = [
            'model' => new ModelExtractReferences(),
            'model_input' => new StackExtractReferences(),
            'stack' => new StackExtractReferences(),
        ];
    }

    public function current()
    {
        return $this->stack[0];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        $reference = array_shift($this->stack);
        $this->position++;
        $data = $this->schema[$reference];
        $type = Schema::getType($data);
        if (!isset($this->extractors[$type])) {
            return;
        }
        $this->stack = array_merge($this->stack, $this->extractors[$type]->extract($data));
    }

    public function rewind()
    {
        $this->stack = ['root'];
        $this->position = 0;
    }

    public function valid()
    {
        return count($this->stack) > 0;
    }
}

class ModelExtractReferences
{
    public function extract($schema)
    {
        $refs = [];
        foreach ($schema['inputs'] as $value) {
            $split = explode(":", $value);
            if (count($split) > 1 && $split[0] === 'reference') {
                $refs[] = $split[1];
            }
        }
        return $refs;
    }
}

class StackExtractReferences
{
    public function extract($schema)
    {
        $refs = [];
        foreach ($schema['items'] as $value) {
            $split = explode(":", $value);
            if (count($split) > 1 && $split[0] === 'reference') {
                $refs[] = $split[1];
            }
        }
        return $refs;
    }
}