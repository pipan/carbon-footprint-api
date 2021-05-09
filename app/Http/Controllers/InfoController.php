<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class InfoController extends Controller
{
    public function __invoke()
    {
        $composerJsonContent = file_get_contents(base_path('composer.json'));
        $composerJson = json_decode($composerJsonContent, true);

        return response([
            'version' => $composerJson['version'] ?? ''
        ]);
    }
}