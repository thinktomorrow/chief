<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\App\View\Livewire\FieldsComponent;
use Thinktomorrow\Chief\App\View\Livewire\Fragments;
use Thinktomorrow\Chief\App\View\Livewire\Links;
use Thinktomorrow\Chief\App\View\Livewire\Status;
use Thinktomorrow\Chief\Site\Urls\UrlHelper;

class ViewServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot(): void
    {
        Paginator::defaultView('chief::pagination.default');
        Paginator::defaultSimpleView('chief::pagination.simple-default');

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
        Livewire::component('fields_component', FieldsComponent::class);
        Livewire::component('status', Status::class);

        Blade::componentNamespace('Thinktomorrow\\Chief\\App\\View\\Components', 'chief');

        Blade::component('chief::components.title', 'chief-title');
        Blade::component('chief::components.content', 'chief-content');
        Blade::component('chief::components.card', 'chief-card');
        Blade::component('chief::components.sidebar', 'chief-sidebar');
        Blade::component('chief::components.inline-notification', 'inline-notification');
        Blade::component('chief::components.icon-label', 'icon-label');
        Blade::component('chief::components.formgroup', 'chief-formgroup');

        Blade::component('chief::wireframes.wireframe', 'wireframe');
        Blade::component('chief::wireframes.container', 'wireframe-container');
        Blade::component('chief::wireframes.row', 'wireframe-row');
        Blade::component('chief::wireframes.column', 'wireframe-column');
        Blade::component('chief::wireframes.title', 'wireframe-title');
        Blade::component('chief::wireframes.text', 'wireframe-text');
        Blade::component('chief::wireframes.image', 'wireframe-image');

        Blade::aliasComponent('chief::back._layouts._partials.header', 'chiefheader');
        Blade::aliasComponent('chief::components.formgroup', 'formgroup');

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
