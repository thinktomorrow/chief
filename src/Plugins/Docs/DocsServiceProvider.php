<?php

namespace Thinktomorrow\Chief\Plugins\Docs;

use InvalidArgumentException;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;

class DocsServiceProvider extends ChiefPluginServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->mergeConfigFrom(__DIR__.'/config/chief-docs.php', 'chief-docs');

        $this->loadDocsFrom(__DIR__.'/resources/docs', 'chief');

        $this->app->scoped(GuideTree::class, function () {
            $admin = chiefAdmin();
            $roleNames = $admin instanceof User ? $admin->getRoleNames()->all() : [];

            return $this->app->make(GuideTreeBuilder::class)
                ->build($this->app->make(DocSources::class))
                ->visibleTo($roleNames, $this->hiddenGuideIds());
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/App/resources/views', 'chief-docs');

        $this->loadPluginAdminRoutes(__DIR__.'/App/chief-admin-routes.php');

        $this->publishes([
            __DIR__.'/config/chief-docs.php' => config_path('chief-docs.php'),
        ], 'chief-docs-config');
    }

    /**
     * @return list<string>
     */
    private function hiddenGuideIds(): array
    {
        $hiddenIds = config('chief-docs.hidden');

        if (! is_array($hiddenIds) || ! array_is_list($hiddenIds)) {
            throw new InvalidArgumentException('Config [chief-docs.hidden] must be a list of guide IDs.');
        }

        foreach ($hiddenIds as $hiddenId) {
            if (! is_string($hiddenId)) {
                throw new InvalidArgumentException('Config [chief-docs.hidden] must only contain guide IDs, got ['.get_debug_type($hiddenId).'].');
            }
        }

        return $hiddenIds;
    }
}
