<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\_partials;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\App\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\ReorderFragments;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyDetached;
use Thinktomorrow\Chief\Fragments\UI\Livewire\FragmentDto;

trait WithFragments
{
    public Collection $fragments;

    private function getListenersWithFragments()
    {
        return [
            'fragment-updated-'.$this->getId() => 'onFragmentUpdated',
            'fragment-isolated-'.$this->getId() => 'onFragmentIsolated',
            'fragment-added-'.$this->getId() => 'onFragmentAdded',
            'fragment-deleting-'.$this->getId() => 'onFragmentDeleting',
            'request-refresh' => '$refresh',
        ];
    }

    public function editFragment(string $fragmentId): void
    {
        $this->dispatch('open-'.$this->getId(), [
            'fragmentId' => $fragmentId,
        ])->to('chief-fragments::edit-fragment');
    }

    public function addFragment(int $order, ?string $parentId = null): void
    {
        $this->dispatch('open-'.$this->getId(), [
            'order' => $order,
            'parentId' => $parentId,
        ])->to('chief-fragments::add-fragment');
    }

    public function onFragmentUpdated(string $fragmentId, string $contextId, ?string $parentId)
    {
        if (! $this->isApplicable($fragmentId, $contextId, $parentId)) {
            return;
        }

        $this->refreshOneFragment($fragmentId);
    }

    public function onFragmentAdded(string $fragmentId, string $contextId, ?string $parentId, int $order): void
    {
        if (! $this->isApplicable($fragmentId, $contextId, $parentId)) {
            return;
        }

        $this->refreshFragments();
    }

    public function onFragmentIsolated(string $fragmentId, string $formerFragmentId, string $contextId, ?string $parentId): void
    {
        if (! $this->isApplicable($fragmentId, $contextId, $parentId)) {
            return;
        }

        $this->refreshOneFragment($fragmentId, $formerFragmentId);
    }

    public function onFragmentDeleting(string $fragmentId, string $contextId, ?string $parentId): void
    {
        if (! $this->isApplicable($fragmentId, $contextId, $parentId)) {
            return;
        }

        try {
            // This detaches the fragment from given context - if the fragment is not shared / used
            // elsewhere it will be deleted completely via listener on the FragmentDetached event
            app(DetachFragment::class)->handle($this->context->id, $fragmentId);
        } catch (FragmentAlreadyDetached $e) {
            //
        }

        $this->refreshFragments();
    }

    public function reorder($fragmentIds)
    {
        app(ReorderFragments::class)->handle($this->context->id, $fragmentIds, isset($this->fragment) ? $this->fragment->fragmentId : null);

        // Reoorder $fragments by given fragmentIds order
        $this->fragments = $this->fragments
            ->map(function (FragmentDto $fragment) use ($fragmentIds) {
                $fragment->order = array_search($fragment->fragmentId, $fragmentIds);

                return $fragment;
            })
            ->sortBy('order');
    }

    private function refreshFragments(): void
    {
        $fragmentCollection = app(FragmentRepository::class)->getFragmentCollection(
            $this->context->id,
            isset($this->fragment) ? $this->fragment->fragmentId : null
        );

        $this->fragments = collect($fragmentCollection->all())
            ->map(fn ($fragment) => FragmentDto::fromFragment($fragment, $this->context));
    }

    /**
     * Refresh a single fragment in the collection.
     *
     * The formerFragmentId is used when a fragment is isolated, which results
     * in the former fragment id being replaced by a (cloned) new one.
     */
    private function refreshOneFragment(string $fragmentId, ?string $formerFragmentId = null): void
    {
        // Include all children as well to provide consistent preview content
        $updatedFragment = $this->findFragment($formerFragmentId ?: $fragmentId)->allowsFragments
            ? app(FragmentRepository::class)->findInFragmentCollection($this->context->id, $fragmentId)
            : app(FragmentRepository::class)->findInContext($fragmentId, $this->context->id);

        // Update given fragment in the fragment collection
        foreach ($this->fragments as $i => $fragment) {
            if ($fragment->fragmentId === ($formerFragmentId ?: $fragmentId)) {
                $this->fragments[$i] = FragmentDto::fromFragment($updatedFragment, $this->context);
            }
        }
    }

    private function findFragment(string $fragmentId): FragmentDto
    {
        return $this->fragments->first(fn (FragmentDto $fragment) => $fragment->fragmentId === $fragmentId);
    }

    /**
     * Checks whether the child fragment event is applicable in this component
     * This is needed because we reuse components such as add-fragment and edit-fragment
     */
    private function isApplicable(string $fragmentId, string $contextId, ?string $parentId): bool
    {
        if ($contextId !== $this->context->id) {
            return false;
        }

        if ($parentId && isset($this->fragment) && $parentId !== $this->fragment->fragmentId) {
            return false;
        }

        return true;
    }
}
