<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\App\View\Livewire\FieldsWindow;
use Thinktomorrow\Chief\App\View\Livewire\Fragments;
use Thinktomorrow\Chief\App\View\Livewire\Links;
use Thinktomorrow\Chief\App\View\Livewire\Status;
use Thinktomorrow\Chief\Site\Urls\UrlHelper;

class ViewServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot(): void
    {
        View::composer([
            'chief::manager._transitions.modals.archive-modal',
        ], function ($view) {
            $viewData = $view->getData();

            $ignoredModel = (isset($viewData['model']))
                ? $viewData['model']
                : null;

            $onlineModels = UrlHelper::allOnlineModels(false, $ignoredModel);

            $view->with('targetModels', $onlineModels);
        });

        Blade::componentNamespace('Thinktomorrow\\Chief\\App\\View\\Components', 'chief');

        // Livewire components
        Livewire::component('fragments', Fragments::class);
        Livewire::component('links', Links::class);
        Livewire::component('fields-window', FieldsWindow::class);
        Livewire::component('status', Status::class);

        /* Livewire component wrappers */
        Blade::component('chief::manager.windows.fields.window', 'chief::window.fields');
        Blade::component('chief::manager.windows.fragments.window', 'chief::window.fragments');
        Blade::component('chief::manager.windows.status.window', 'chief::window.status');
        Blade::component('chief::manager.windows.links.window', 'chief::window.links');

        /* Chief components */
        Blade::component('chief::components.window', 'chief::window');
        Blade::component('chief::components.title', 'chief-title');
        Blade::component('chief::components.content', 'chief-content');
        Blade::component('chief::components.card', 'chief-card');
        Blade::component('chief::components.sidebar', 'chief-sidebar');
        Blade::component('chief::components.inline-notification', 'chief-inline-notification');
        Blade::component('chief::components.icon-label', 'chief-icon-label');
        Blade::component('chief::components.icon-button', 'chief-icon-button');
        Blade::component('chief::components.hierarchy', 'chief-hierarchy');
        Blade::component('chief::components.nav-item', 'chief::nav.item');

        /* Chief field components */
        Blade::component('chief::components.field.window.multiple', 'chief::fields');
        Blade::component('chief::components.field.window.single', 'chief::field');
        Blade::component('chief::components.field.form.single', 'chief::field.form');
        Blade::component('chief::components.field.form.multiple', 'chief::fields.form');
        Blade::component('chief::components.field.form.error', 'chief::field.form.error');
        Blade::component('chief::components.field.set', 'chief::field.set');

        /* Wireframe components */
        Blade::component('chief::wireframes.wireframe', 'wireframe');
        Blade::component('chief::wireframes.container', 'wireframe-container');
        Blade::component('chief::wireframes.row', 'wireframe-row');
        Blade::component('chief::wireframes.column', 'wireframe-column');
        Blade::component('chief::wireframes.title', 'wireframe-title');
        Blade::component('chief::wireframes.text', 'wireframe-text');
        Blade::component('chief::wireframes.image', 'wireframe-image');
        Blade::component('chief::wireframes.video', 'wireframe-video');
        Blade::component('chief::wireframes.rect', 'wireframe-rect');

        /* Chief directives */
        Blade::directive('fragments', function () {
            return "<?php echo app(\Thinktomorrow\Chief\Fragments\FragmentsRenderer::class)->render(\$model, get_defined_vars()); ?>";
        });
        Blade::directive('adminConfig', function ($expression = null) {
            if ($expression) {
                $method = "get".ucfirst(str_replace("'", '', $expression));

                return "<?php echo \$model->adminConfig()->$method(); ?>";
            }

            return "<?php echo \$model->adminConfig(); ?>";
        });
        Blade::directive('adminRoute', function ($expression) {
            return "<?php echo \$manager->route($expression); ?>";
        });
        Blade::directive('adminCan', function ($expression) {
            return "<?php if (\$manager->can($expression)) { ?>";
        });
        Blade::directive('elseAdminCan', function () {
            return "<?php } else { ?>";
        });
        Blade::directive('endAdminCan', function () {
            return "<?php } ?>";
        });
    }

    public function register()
    {
        //
    }
}
