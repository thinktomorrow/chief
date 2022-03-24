<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Site\Urls\UrlHelper;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'chief');

        $this->app['view']->addNamespace('chief-fragments', __DIR__.'/../../src/Fragments/resources');

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

        // Wireframe components
        Blade::component('chief::wireframes.wireframe', 'wireframe');
        Blade::component('chief::wireframes.container', 'wireframe-container');
        Blade::component('chief::wireframes.row', 'wireframe-row');
        Blade::component('chief::wireframes.column', 'wireframe-column');
        Blade::component('chief::wireframes.title', 'wireframe-title');
        Blade::component('chief::wireframes.text', 'wireframe-text');
        Blade::component('chief::wireframes.image', 'wireframe-image');
        Blade::component('chief::wireframes.video', 'wireframe-video');
        Blade::component('chief::wireframes.rect', 'wireframe-rect');

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
