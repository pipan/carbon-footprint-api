<?php

namespace App\Schema\Minify;

class ChainMinifier implements Minifier
{
    private $minifiers;

    public function __construct($minifiers)
    {
        $this->minifiers = $minifiers;
    }

    public function minify($schema)
    {
        foreach ($this->minifiers as $minifier) {
            $schema = $minifier->minify($schema);
        }
        
        return $schema;
    }
}