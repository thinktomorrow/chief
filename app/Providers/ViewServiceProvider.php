<?php

namespace Thinktomorrow\Chief\App\Providers;

use Livewire\Livewire;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Site\Urls\UrlHelper;
use Thinktomorrow\Chief\App\View\Livewire\Links;
use Thinktomorrow\Chief\App\View\Livewire\Fragments;

class ViewServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        View::composer([
            'chief::back.managers._modals.archive-modal',
            'chief::back.catalogpages._modals.archive-modal',
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

        Blade::componentNamespace('Thinktomorrow\\Chief\\App\\View\\Components', 'chief');

        Blade::component('chief::layouts.app', 'chief-app-layout');
        Blade::component('chief::components.title', 'chief-title');
        Blade::component('chief::components.content', 'chief-content');
        Blade::component('chief::components.card', 'chief-card');
        Blade::component('chief::components.sidebar', 'chief-sidebar');

        Blade::aliasComponent('chief::back._layouts._partials.header', 'chiefheader');
        Blade::aliasComponent('chief::back._components.formgroup', 'formgroup');
        Blade::aliasComponent('chief::back._components.expand', 'expand');

        Blade::directive('fragments', function ($expression) {
            return "<?php echo app(\Thinktomorrow\Chief\Fragments\FragmentsRenderer::class)->render(\$model, get_defined_vars()); ?>";
        });

        Blade::directive('adminLabel', function ($expression) {
            return "<?php echo \$model->adminLabel($expression); ?>";
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
