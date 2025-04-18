<?php

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Context\AddContext;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Context\Context;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Context\Contexts;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Context\EditContext;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Fragment\AddFragment;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Fragment\EditFragment;

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

        Livewire::component('chief-fragments::contexts', Contexts::class);
        Livewire::component('chief-wire::edit-context', EditContext::class);
        Livewire::component('chief-wire::add-context', AddContext::class);
        Livewire::component('chief-fragments::context', Context::class);
        Livewire::component('chief-fragments::add-fragment', AddFragment::class);
        Livewire::component('chief-fragments::edit-fragment', EditFragment::class);
    }
}
