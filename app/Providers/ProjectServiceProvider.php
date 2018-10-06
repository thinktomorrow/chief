<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Management\Register;

class ProjectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Core managers
        // ...
    }

    public function register()
    {
        //
    }

    public function registerManager($key, $class, $model)
    {
        return app(Register::class)->register($key, $class, $model);
    }
}
