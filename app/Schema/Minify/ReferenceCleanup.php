<?php

namespace App\Schema\Minify;

class ReferenceCleanup implements Minifier
{
    public function minify($schema)
    {
        $iterator = new ReferenceBreadthFirstIterator($schema);
        $usedRefs = [];
        foreach ($iterator as $ref) {
            $usedRefs[] = $ref;
        }

        $result = [];
        foreach ($usedRefs as $ref) {
            $result[$ref] = $schema[$ref];
        }
        return $result;
    }
}