<?php

namespace App\Schema\Minify;

class DummyMinifier implements Minifier
{
    public function minify($schema)
    {
        return $schema;
    }
}