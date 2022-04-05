<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'chief');

        // Fragment components
        $this->app['view']->addNamespace('chief-fragments', __DIR__ . '/../../src/Fragments/resources');

        Blade::componentNamespace('Thinktomorrow\\Chief\\App\\View\\Components', 'chief');
        Blade::component('chief::manager.windows.status.window', 'chief::window.status');
        Blade::component('chief::manager.windows.links.window', 'chief::window.links');

        // Chief components
        Blade::component('chief::components.title', 'chief-title');
        Blade::component('chief::components.content', 'chief-content');
        Blade::component('chief::components.sidebar', 'chief-sidebar');
        Blade::component('chief::components.inline-notification', 'chief-inline-notification');
        Blade::component('chief::components.icon-label', 'chief-icon-label');
        Blade::component('chief::components.icon-button', 'chief-icon-button');
        Blade::component('chief::components.hierarchy', 'chief-hierarchy');
        Blade::component('chief::components.nav-item', 'chief::nav.item');

        // Chief directives
        Blade::directive('adminRoute', function ($expression) {
            return "<?php echo \$manager->route({$expression}); ?>";
        });

        Blade::directive('adminCan', function ($expression) {
            return "<?php if (\$manager->can({$expression})) { ?>";
        });

        Blade::directive('elseAdminCan', function () {
            return '<?php } else { ?>';
        });

        Blade::directive('endAdminCan', function () {
            return '<?php } ?>';
        });

        // TODO(ben): better solution for this ugly mess
        // Hello Tijs. We meet again
        $isCollapsedOnPageLoad =
            isset($_COOKIE['is-navigation-collapsed'])
            ? filter_var($_COOKIE['is-navigation-collapsed'], FILTER_VALIDATE_BOOLEAN)
            : false;
        view()->share('isCollapsedOnPageLoad', $isCollapsedOnPageLoad);
    }

    public function register()
    {
    }
}
