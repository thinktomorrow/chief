<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Fragments\StaticFragmentManager;
use Thinktomorrow\Chief\Legacy\Fragments\FragmentModel;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Managers\Register\RegisterManager;
use Thinktomorrow\Chief\Modules\Presets\PagetitleModule;
use Thinktomorrow\Chief\Modules\Presets\PagetitleModuleManager;
use Thinktomorrow\Chief\Modules\Presets\TextModule;
use Thinktomorrow\Chief\Modules\Presets\TextModuleManager;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Site\Menu\MenuItem;

class ChiefProjectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Out of the box morphables - the key 'singles' is the page's default morphKey.
        Relation::morphMap([
            'menuitem' => MenuItem::class,
            'singles' => Single::class,
            'text' => TextModule::class,
            'pagetitle' => PagetitleModule::class,
            'fragment' => FragmentModel::class,
        ]);

        // singles
//        $this->registerPage(PageManager::class, Single::class);

//        $this->registerManager(StaticFragmentManager::class);
//        $this->registerModel(TextModule::class, TextModuleManager::class,'module');
//        $this->registerManager(PagetitleModuleManager::class, ['module']);
    }

    protected function registerModel(string $modelClass, string $managerClass, $tags = []): void
    {
        $this->app->make(Register::class)->model($modelClass, $managerClass, $tags);
    }

    protected function registerFragments(array $fragmentClasses): void
    {
        foreach ($fragmentClasses as $fragmentClass) {
            $this->app->make(Register::class)->staticFragment($fragmentClass);
        }
    }

    public function register()
    {
        //
    }
}
