<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Pages\Single;

class ProjectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Out of the box morphables - the key 'singles' is the page's default morphKey.
        Relation::morphMap([
            'singles' => Single::class,
        ]);
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
