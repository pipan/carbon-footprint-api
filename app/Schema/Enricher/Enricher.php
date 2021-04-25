<?php

namespace App\Schema\Enricher;

interface Enricher
{
    public function enrich($schema);
}