<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Register;

use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Relations\Relation;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Presets\FragmentManager;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Managers\Request\ManagerRequestDispatcher;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoutes;
use Thinktomorrow\Chief\Managers\Routes\RegisterManagedRoutes;

final class Register
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function fragment(string $fragmentClass, $tags = []): void
    {
        // TODO: this reduces memory with around 1MB which is nice for frontend visits. But this removes the possibility to use the chief toast on frontend to get the admin edit url
        // option is to load up the url async when logged in... Is there a better way to isolate this route loading only when logged in as chief admin?
        // If the request isn't an chief admin request - we don't load up the managers and routes.
        // option 2: only after event: allowedAdminVisit
//        if(!Request::is(['admin', 'admin/*'])) {
//            $this->registerMorphMap($fragmentClass);
//            return;
//        }
        $this->register($fragmentClass, $this->container->makeWith(FragmentManager::class, ['managedModelClass' => $fragmentClass]), $tags, );
    }

    public function model(string $modelClass, string $managerClass = PageManager::class, $tags = ['nav']): void
    {
        // If the request isn't an chief admin request - we don't load up the managers and routes.
//        if(!Request::is(['admin', 'admin/*'])) {
//            $this->registerMorphMap($modelClass);
//            return;
//        }

        $this->register($modelClass, $this->container->makeWith($managerClass, ['managedModelClass' => $modelClass]), $tags);
    }

    private function register(string $modelClass, Manager $manager, $tags = []): void
    {
        // Check if model class implements ManagedModel interface
        $ref = new \ReflectionClass($modelClass);
        if (! $ref->implementsInterface(ManagedModel::class)) {
            throw new \DomainException('Class '.$modelClass.' should implement contract '.ManagedModel::class);
        }

        $managedModelKey = $modelClass::managedModelKey();

        // Only load up the admin routes and managers when in admin...
//        if(chiefAdmin()) {
        // Add to chief registry
        $this->container->make(Registry::class)
            ->registerModel($managedModelKey, $modelClass)
            ->registerManager($managedModelKey, $manager)
            ->registerTags($managedModelKey, (array) $tags)
        ;

        // Register routes
        $this->container->make(RegisterManagedRoutes::class)(
            $manager,
            ManagedRoutes::empty(get_class($manager), $managedModelKey),
            ManagerRequestDispatcher::class,
        );
//        }

        $this->registerMorphMap($modelClass);
    }

    private function registerMorphMap(string $modelClass)
    {
        $managedModelKey = $modelClass::managedModelKey();

        // Add to eloquent db morph map
        Relation::morphMap([
            $managedModelKey => $modelClass,
        ]);
    }
}
