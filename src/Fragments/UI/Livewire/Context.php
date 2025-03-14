<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Fragments\App\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\ReorderFragments;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyDetached;

class Context extends Component
{
    public ContextDto $context;

    public Collection $fragments;

    public function mount(ContextDto $context)
    {
        $this->context = $context;
        $this->refreshFragments();
    }

    public function getListeners()
    {
        return [
            'root-fragment-added' => 'onRootFragmentAdded',
            'fragment-deleting' => 'onFragmentDeleting',
            'request-refresh' => '$refresh',
        ];
    }

    /** @return Collection<FragmentDto> */
    //    public function getRootFragments(): Collection
    //    {
    //        $fragmentCollection = app(FragmentRepository::class)->getFragmentCollection($this->context->contextId);
    //
    //        return collect($fragmentCollection->all())
    //            ->map(fn ($fragment) => FragmentDto::fromFragment($fragment, $this->context));
    //    }

    public function reorder($fragmentIds)
    {
        app(ReorderFragments::class)->handle($this->context->contextId, $fragmentIds);

        // Reoorder $fragments by given fragmentIds order
        $this->fragments = $this->fragments->sortBy(fn (FragmentDto $fragment) => array_search($fragment->fragmentId, $fragmentIds));

        // $this->refreshFragments();

        //        $this->dispatch('fragments-reordered', ...[
        //            'fragmentIds' => $fragmentIds,
        //        ]);
    }

    private function refreshFragments(): void
    {
        $fragmentCollection = app(FragmentRepository::class)->getFragmentCollection($this->context->contextId);

        $this->fragments = collect($fragmentCollection->all())
            ->map(fn ($fragment) => FragmentDto::fromFragment($fragment, $this->context));
    }

    public function addFragment(int $order, ?string $parentId = null): void
    {
        $this->dispatch('open-'.$this->getId(), [
            'order' => $order,
            'parentId' => $parentId,
        ])->to('chief-fragments::add-fragment');
    }

    public function onRootFragmentAdded(string $fragmentId, string $contextId, ?string $parentId, int $order): void
    {
        if ($contextId !== $this->context->contextId) {
            return;
        }

        $this->refreshSelf();
    }

    public function onFragmentIsolated(string $fragmentId, string $formerFragmentId, string $contextId): void
    {
        if ($contextId !== $this->context->contextId) {
            return;
        }

        $this->refreshSelf();
    }

    public function onFragmentDeleting(string $fragmentId, string $contextId): void
    {
        if ($contextId !== $this->context->contextId) {
            return;
        }

        try {
            // This detaches the fragment from given context - if the fragment is not shared / used
            // elsewhere it will be deleted completely via listener on the FragmentDetached event
            app(DetachFragment::class)->handle($this->context->contextId, $fragmentId);
        } catch (FragmentAlreadyDetached $e) {
            //
        }
    }

    private function refreshSelf(): void
    {
        $this->reorder($this->getFragmentIdsInOrder());

        $this->dispatch('request-refresh')->self();
    }

    public function render()
    {
        return view('chief-fragments::livewire.context');
    }

    private function getFragmentIdsInOrder(): array
    {
        return $this->getRootFragments()
            ->map(fn (FragmentDto $fragment) => $fragment->fragmentId)
            ->all();
    }
}
