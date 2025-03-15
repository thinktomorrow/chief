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
            'fragment-updated' => 'onFragmentUpdated',
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
        $this->fragments = $this->fragments
            ->map(function (FragmentDto $fragment) use ($fragmentIds) {
                $fragment->order = array_search($fragment->fragmentId, $fragmentIds);

                return $fragment;
            })
            ->sortBy('order');

        // $this->refreshFragments();

        //        $this->dispatch('fragments-reordered', ...[
        //            'fragmentIds' => $fragmentIds,
        //        ]);
    }

    public function onFragmentUpdated(string $fragmentId, string $contextId)
    {
        if ($contextId !== $this->context->contextId) {
            return;
        }

        $this->refreshFragments($fragmentId);
    }

    private function refreshFragments(): void
    {
        $fragmentCollection = app(FragmentRepository::class)->getFragmentCollection($this->context->contextId);

        $this->fragments = collect($fragmentCollection->all())
            ->map(fn ($fragment) => FragmentDto::fromFragment($fragment, $this->context));
    }

    private function refreshOneFragment(string $fragmentId): void
    {
        $updatedFragment = app(FragmentRepository::class)->findById($fragmentId, $this->context->contextId);

        // Update given fragment in the fragment collection
        foreach ($this->fragments as $i => $fragment) {
            if ($fragment->fragmentId === $fragmentId) {
                $this->fragments[$i] = FragmentDto::fromFragment($updatedFragment, $this->context);
            }
        }
    }

    public function editFragment(string $fragmentId): void
    {
        $fragment = $this->fragments->first(fn (FragmentDto $fragment) => $fragment->fragmentId === $fragmentId);

        if (! $fragment) {
            throw new \InvalidArgumentException('Fragment not found by id: '.$fragmentId);
        }

        $this->dispatch('open-'.$this->getId(), [
            'fragment' => $fragment->toLivewire(),
        ])->to('chief-fragments::edit-fragment');
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

        $this->refreshFragments();
    }

    public function onFragmentIsolated(string $fragmentId, string $formerFragmentId, string $contextId): void
    {
        if ($contextId !== $this->context->contextId) {
            return;
        }

        $this->refreshFragments();
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

        $this->refreshFragments();
    }

    //    private function refreshSelf(): void
    //    {
    //        $this->reorder($this->getFragmentIdsInOrder());
    //
    //        $this->dispatch('request-refresh')->self();
    //    }

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
