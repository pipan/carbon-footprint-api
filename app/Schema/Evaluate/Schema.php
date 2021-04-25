<?php

namespace App\Schema\Evaluate;

class Schema
{
    public static function getType($schema)
    {
        if (is_array($schema)) {
            return $schema['type'];
        }
        $parts = explode(":", $schema);
        if (count($parts) === 1) {
            return 'operation';
        }
        return $parts[0];
    }

    public static function isOperation($schema)
    {
        return Schema::getType($schema) === 'operation';
    }
}