<?php

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;

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

        Livewire::addNamespace(
            'chief-wire-fragments',
            classNamespace: 'Thinktomorrow\\Chief\\Fragments\\UI\\Livewire',
            classPath: __DIR__.'/UI/Livewire',
            classViewPath: __DIR__.'/UI/views/livewire',
        );
    }
}
