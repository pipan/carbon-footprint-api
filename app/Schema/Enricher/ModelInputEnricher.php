<?php

namespace App\Schema\Enricher;

use App\Repository\ModelRepository;
use App\Schema\Evaluate\Schema;

class ModelInputEnricher implements Enricher
{
    public function enrich($schema)
    {
        $modelInputRefs = [];
        foreach ($schema as $reference => $value) {
            $type = Schema::getType($value);
            if ($type != 'model_input') {
                continue;
            }
            $modelInputRefs[] = $reference;
        }
        
        foreach ($modelInputRefs as $ref) {
            $parentRef = $schema[$ref]['parent'];
            $inputReference = array_search("reference:" . $ref, $schema[$parentRef]['inputs']);
            if (!$inputReference) {
                continue;
            }

            $inputModel = [];
            foreach ($schema[$parentRef]['model']['inputs'] as $input) {
                if ($input['reference'] === $inputReference) {
                    $inputModel = $input;
                    break;
                }
            }
            $schema[$ref]['model'] = $inputModel;
        }

        return $schema;
    }
}