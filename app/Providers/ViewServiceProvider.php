<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'chief');

        Blade::componentNamespace('Thinktomorrow\\Chief\\App\\View\\Components', 'chief');

        // Manager components
        Blade::component('chief::manager.windows.state.windows', 'chief::window.states');
        Blade::component('chief::manager.windows.links.window', 'chief::window.links');

        // Template components
        Blade::component('chief::templates.page.layout', 'chief::page.layout');
        Blade::component('chief::templates.page.template', 'chief::page.template');
        Blade::component('chief::templates.page.header', 'chief::page.header');
        Blade::component('chief::templates.solo.layout', 'chief::solo.layout');
        Blade::component('chief::templates.solo.template', 'chief::solo.template');
        Blade::component('chief::templates.mail.layout', 'chief::mail.layout');
        Blade::component('chief::templates.mail.template', 'chief::mail.template');

        // Chief directives
        Blade::directive('adminRoute', function ($expression) {
            return "<?php echo \$manager->route({$expression}); ?>";
        });

        Blade::directive('adminCan', function ($expression) {
            return "<?php if (isset(\$manager) && \$manager->can({$expression})) { ?>";
        });

        Blade::directive('elseAdminCan', function () {
            return '<?php } else { ?>';
        });

        Blade::directive('endAdminCan', function () {
            return '<?php } ?>';
        });
    }

    public function register() {}
}
