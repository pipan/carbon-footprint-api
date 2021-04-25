<?php

namespace App\Schema\Minify;

class WhitelistMinifier implements Minifier
{
    private $keys;

    public function __construct($keys)
    {
        $this->keys = $keys;
    }

    public function minify($schema)
    {
        $result = [];
        foreach ($this->keys as $key) {
            if (!isset($schema[$key])) {
                continue;
            }
            $result[$key] = $schema[$key];
        }
        return $result;
    }
}