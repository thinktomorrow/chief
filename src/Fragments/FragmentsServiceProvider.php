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

        /**
         * @deprecated use getFragments() instead
         *
         * This directive does not make use of the component rendering of fragments.
         * Best to loop the fragments in the view like:
         *
         * @foreach(getFragments() as $fragment) {{ $fragment->render() }} @endforeach
         */
        Blade::directive('fragments', function () {
            return '<?php foreach(getFragments() as $fragment): ?><?= $fragment->render(); ?><?php endforeach; ?>';
        });
    }

    public function bootAdmin(): void
    {
        $this->app['view']->addNamespace('chief-fragments', __DIR__.'/UI/views');

        Blade::component(SidebarFragment::class, 'chief-fragments::sidebar');
        Blade::component(Fragments::class, 'chief-fragments::index');
    }
}
