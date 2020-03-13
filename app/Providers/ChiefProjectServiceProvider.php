<?php

namespace Thinktomorrow\Chief\App\Providers;

use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Menu\MenuItem;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Modules\TextModule;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Modules\ModuleManager;
use Thinktomorrow\Chief\Modules\PagetitleModule;
use Illuminate\Database\Eloquent\Relations\Relation;

class ChiefProjectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Out of the box morphables - the key 'singles' is the page's default morphKey.
        Relation::morphMap([
            'menuitem'  => MenuItem::class,
            'singles'   => Single::class,
            'text'      => TextModule::class,
            'pagetitle' => PagetitleModule::class,
        ]);

        // singles - text - pagetitle
        $this->registerPage(PageManager::class, Single::class);

        $this->registerManager(ModuleManager::class, TextModule::class, ['pagesection']);
        $this->registerManager(ModuleManager::class, PagetitleModule::class, ['pagesection']);
    }

    public function registerModule($class, $model, array $tags = [])
    {
        return $this->registerManager($class, $model, array_merge(['module'], $tags));
    }

    public function registerPage($class, $model, array $tags = [])
    {
        return $this->registerManager($class, $model, array_merge(['page'], $tags));
    }

    public function registerManager($class, $model, array $tags = [])
    {
        return app(Register::class)->register($class, $model, $tags);
    }

    public function register()
    {
        //
    }
}
