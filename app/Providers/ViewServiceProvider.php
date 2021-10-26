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

        Livewire::component('fragments', Fragments::class);
        Livewire::component('links', Links::class);
        Livewire::component('fields-window', FieldsWindow::class);
        Livewire::component('status', Status::class);

        Blade::componentNamespace('Thinktomorrow\\Chief\\App\\View\\Components', 'chief');

        /* Chief components */
        Blade::component('chief::components.field.field', 'chief::field');
        Blade::component('chief::components.field.error', 'chief::field.error');
        Blade::component('chief::components.field.input', 'chief::field.input');
        Blade::component('chief::components.field.multiple', 'chief::field.multiple');

        Blade::component('chief::components.page.fragments-window', 'chief::fragments.window');
        Blade::component('chief::components.page.status-window', 'chief::status.window');
        Blade::component('chief::components.page.links-window', 'chief::links.window');


        Blade::component('chief::components.title', 'chief-title');
        Blade::component('chief::components.content', 'chief-content');
        Blade::component('chief::components.card', 'chief-card');
        Blade::component('chief::components.sidebar', 'chief-sidebar');
        Blade::component('chief::components.inline-notification', 'chief-inline-notification');
        Blade::component('chief::components.icon-label', 'chief-icon-label');
//        Blade::component('chief::components.formgroup', 'chief-formgroup');
        Blade::component('chief::components.hierarchy', 'chief-hierarchy');

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
