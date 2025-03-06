<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Fragments\App\Actions\ReorderFragments;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentCollection;

class Context extends Component
{
    public string $contextId;

    public ContextDto $context;

    public function mount(string $contextId, ContextModel $originalContext)
    {
        $this->contextId = $contextId;

        $this->context = ContextDto::fromContext($originalContext);
    }

    public function getFragments(): FragmentCollection
    {
        return app(FragmentRepository::class)->getFragmentCollection($this->contextId);
    }

    public function reorder($orderedIds)
    {
        app(ReorderFragments::class)->handle($this->contextId, $orderedIds);
    }

    public function render()
    {
        return view('chief-fragments::livewire.context');
    }
}
