<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Fragments\App\Actions\ReorderFragments;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;

class Context extends Component
{
    public string $contextId;

    public ContextDto $context;

    public function mount(ContextDto $context)
    {
        $this->context = $context;
    }

    public function getListeners()
    {
        return [
            'root-fragment-added' => 'onRootFragmentAdded',
            'fragment-deleted' => 'onFragmentDeleted',
            //            'fragment-updated' => 'onFragmentUpdated',
            'request-refresh' => '$refresh',
        ];
    }

    /** @return Collection<FragmentDto> */
    public function getRootFragments(): Collection
    {
        $fragmentCollection = app(FragmentRepository::class)->getFragmentCollection($this->context->contextId);

        return collect($fragmentCollection->all())
            ->map(fn ($fragment) => FragmentDto::fromFragment($fragment, $this->context));
    }

    public function reorder($fragmentIds)
    {
        app(ReorderFragments::class)->handle($this->context->contextId, $fragmentIds);

        $this->dispatch('fragments-reordered', ...[
            'fragmentIds' => $fragmentIds,
        ]);
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

    public function onFragmentDeleted(string $fragmentId, string $contextId, ?string $parentId): void
    {
        if ($contextId !== $this->context->contextId) {
            return;
        }

        $this->refreshSelf();
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
