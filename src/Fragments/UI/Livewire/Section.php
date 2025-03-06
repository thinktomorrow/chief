<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Fragments\App\Actions\ReorderFragments;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\FragmentCollection;

class Section extends Component
{
    public string $contextId;

    public string $fragmentId;

    public FragmentDto $fragment;

    public function mount(Fragment $originalFragment, ContextDto $context)
    {
        $this->contextId = $context->contextId;
        $this->fragmentId = $originalFragment->getFragmentId();

        $this->fragment = FragmentDto::fromFragment(
            $originalFragment,
            $context,
        );
    }

    public function getFragments(): FragmentCollection
    {
        return app(FragmentRepository::class)->getFragmentCollection($this->contextId);
    }

    public function reorder($orderedIds)
    {
        app(ReorderFragments::class)->handle($this->contextId, $orderedIds);
    }

    public function editFragment(): void
    {
        $this->openFragmentDialog([
            'fragmentId' => $this->fragmentId,
        ]);
    }

    private function openFragmentDialog($params): void
    {
        $this->dispatch('open-'.$this->getId(), $params)->to('chief-fragments::edit-fragment');
    }

    public function onFragmentDialogSaved($values): void
    {
        dd('saved', $values);
    }

    public function render()
    {
        return view('chief-fragments::livewire.section');
    }
}
