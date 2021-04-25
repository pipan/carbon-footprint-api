<?php

namespace App\Schema\Minify;

interface Minifier
{
    public function minify($schema);
}