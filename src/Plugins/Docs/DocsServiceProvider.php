<?php

namespace Thinktomorrow\Chief\Plugins\Docs;

use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;

class DocsServiceProvider extends ChiefPluginServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->loadDocsFrom(__DIR__.'/resources/docs', 'chief');

        $this->app->scoped(GuideTree::class, function () {
            $admin = chiefAdmin();
            $roleNames = $admin instanceof User ? $admin->getRoleNames()->all() : [];

            return $this->app->make(GuideTreeBuilder::class)
                ->build($this->app->make(DocSources::class))
                ->visibleTo($roleNames);
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/App/resources/views', 'chief-docs');

        $this->loadPluginAdminRoutes(__DIR__.'/App/chief-admin-routes.php');
    }
}
