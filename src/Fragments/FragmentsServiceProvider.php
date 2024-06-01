<?php

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\UI\Components\Fragments;
use Thinktomorrow\Chief\Fragments\UI\Components\SidebarFragment;

class FragmentsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Relation::morphMap([FragmentModel::resourceKey() => FragmentModel::class]);

        Blade::directive('fragments', function () {
            return '<?php echo app(\\Thinktomorrow\\Chief\\Fragments\\Render\\RenderFragments::class)->render(\\Thinktomorrow\\Chief\\Fragments\\Render\\ActiveContextId::get(), get_defined_vars()); ?>';
        });
    }

    public function bootAdmin(): void
    {
        $this->app['view']->addNamespace('chief-fragments', __DIR__ . '/UI/views');

        Blade::component(SidebarFragment::class, 'chief-fragments::sidebar');
        Blade::component(Fragments::class, 'chief-fragments::index');
    }
}
