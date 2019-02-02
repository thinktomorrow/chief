<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Modules\ModuleManager;
use Thinktomorrow\Chief\Modules\PagetitleModule;
use Thinktomorrow\Chief\Modules\TextModule;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Pages\Single;

class ChiefProjectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Out of the box morphables - the key 'singles' is the page's default morphKey.
        Relation::morphMap([
            'singles'   => Single::class,
            'text'      => TextModule::class,
            'pagetitle' => PagetitleModule::class,
        ]);

        $this->registerPage('singles', PageManager::class, Single::class);

        $this->registerManager('text', ModuleManager::class, TextModule::class, ['pagesection']);
        $this->registerManager('pagetitle', ModuleManager::class, PagetitleModule::class, ['pagesection']);
    }

    public function registerModule($key, $class, $model, array $tags = [])
    {
        return $this->registerManager($key, $class, $model, array_merge(['module'], $tags));
    }

    public function registerPage($key, $class, $model, array $tags = [])
    {
        return $this->registerManager($key, $class, $model, array_merge(['page'], $tags));
    }

    public function registerManager($key, $class, $model, array $tags = [])
    {
        return app(Register::class)->register($key, $class, $model, $tags);
    }

    public function register()
    {
        //
    }
}
