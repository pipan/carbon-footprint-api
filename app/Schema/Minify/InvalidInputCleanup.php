<?php

namespace App\Schema\Minify;

class InvalidInputCleanup implements Minifier
{
    private $inputMap;

    public function __construct($inputs)
    {
        $this->inputMap = [];
        foreach ($inputs as $input) {
            $this->inputMap[$input['reference']] = $input;
        }
    }

    public function minify($schema)
    {
        $toRemove = [];
        foreach ($schema as $key => $value) {
            if ($value['type'] !== 'input') {
                continue;
            }
            $ref = $value['reference'];
            if (isset($this->inputMap[$ref])) {
                continue;
            }
            $toRemove[] = $key;
        }
        
        foreach ($toRemove as $reference) {
            $parentReference = $schema[$reference]['parent'];
            $index = array_search("reference:" . $reference, $schema[$parentReference]['items']);
            if ($index > 0) {
                $index -= 1;
            }
            array_splice($schema[$parentReference]['items'], $index, 2);
        }

        return $schema;
    }
}