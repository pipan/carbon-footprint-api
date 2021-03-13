<?php

namespace App\Providers;

use App\Repository\EloquentModelRepository;
use App\Repository\EloquentUnitRepository;
use App\Repository\ModelRepository;
use App\Repository\UnitRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UnitRepository::class, EloquentUnitRepository::class);
        $this->app->bind(ModelRepository::class, EloquentModelRepository::class);
    }

    public function boot()
    {
        //
    }
}
