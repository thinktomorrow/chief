<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;

class Fragment extends Component
{
    public FragmentDto $fragment;

    public function mount(FragmentDto $fragment)
    {
        $this->fragment = $fragment;
    }

    public function getListeners()
    {
        return [
            'fragment-updated' => 'onFragmentUpdated',
            'fragment-isolated' => 'onFragmentIsolated',
            'fragments-reordered' => 'onFragmentsReordered',
            'fragment-deleted' => 'onFragmentDeleted',
        ];
    }

    public function onFragmentUpdated(string $fragmentId, string $contextId)
    {
        if ($this->fragment->fragmentId === $fragmentId) {
            $this->refreshSelf();
        }
    }

    public function onFragmentIsolated(string $fragmentId, string $formerFragmentId, string $contextId): void
    {
        if ($this->fragment->fragmentId === $formerFragmentId) {

            $this->fragment = $this->fragment->changeFragmentId($fragmentId);

            $this->refreshSelf();
        }
    }

    public function onFragmentsReordered(array $fragmentIds)
    {
        if (! in_array($this->fragment->fragmentId, $fragmentIds)) {
            return;
        }

        $order = array_search($this->fragment->fragmentId, $fragmentIds);

        $this->fragment = $this->fragment->changeOrder($order);
    }

    public function onFragmentDeleting(string $fragmentId, string $contextId, ?string $parentId): void
    {
        if (! $parentId || $parentId !== $this->fragment->fragmentId) {
            return;
        }

        //        $this->refreshSelf();
    }

    private function refreshSelf(): void
    {
        $this->fragment = FragmentDto::fromFragment(
            app(FragmentRepository::class)->findInContext($this->fragment->fragmentId, $this->fragment->contextId),
            app(ComposeLivewireDto::class)->getContext($this->fragment->contextId)
        );
    }

    public function editFragment(): void
    {
        $this->openFragmentDialog([
            'fragmentId' => $this->fragment->fragmentId,
        ]);
    }

    private function openFragmentDialog($params): void
    {
        $this->dispatch('open-'.$this->getId(), $params)->to('chief-fragments::edit-fragment');
    }

    public function render()
    {
        return view('chief-fragments::livewire.fragment');
    }
}
