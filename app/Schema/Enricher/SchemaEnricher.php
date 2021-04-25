<?php

namespace App\Schema\Enricher;

use App\Repository\ModelRepository;

class SchemaEnricher implements Enricher
{
    private $enrichers;

    public function __construct(ModelRepository $modelRepository)
    {
        $this->enrichers = [
            new ModelEnricher($modelRepository),
        ];
    }

    public function enrich($schema)
    {
        foreach ($this->enrichers as $enricher) {
            $schema = $enricher->enrich($schema);
        }
        return $schema;
    }
}