<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\Fragment;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextOwnerRepository;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\UI\Livewire\_partials\AddsExistingFragments;
use Thinktomorrow\Chief\Fragments\UI\Livewire\_partials\AddsNewFragments;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Context\ContextDto;

class AddFragment extends Component
{
    use AddsExistingFragments;
    use AddsNewFragments;
    use ShowsAsDialog;

    public ContextDto $context;

    public ?string $parentComponentId; // parent livewire component id

    public ?string $parentId = null; // parent fragment id

    public ?int $insertAfterOrder = null;

    public array $localeValuesForNewFragment = [];

    public function mount(ContextDto $context, ?string $parentComponentId = null)
    {
        $this->context = $context;
        $this->parentComponentId = $parentComponentId;
    }

    public function getListeners()
    {
        return [
            'open-'.$this->parentComponentId => 'open',
            'files-updated' => 'onFilesUpdated',
        ];
    }

    public function open($values = [])
    {
        $this->parentId = $values['parentId'] ?: null;
        $this->insertAfterOrder = $values['order'];

        // On open we keep track of the passed locales for the new fragment dialog
        $this->localeValuesForNewFragment = [
            'locales' => $values['locales'],
            'scopedLocale' => $values['scopedLocale'],
        ];

        $this->isOpen = true;

        $this->dispatch('form-dialog-opened', ...[
            'componentId' => $this->getId(),
            'parentComponentId' => $this->parentComponentId,
            'contextId' => $this->context->id,
        ]);
    }

    public function close()
    {
        $this->reset([
            'form', 'parentId', 'insertAfterOrder',
            'showCreate', 'fragmentKey', // properties for new fragments
            'filters', // properties for existing fragments
            'locales', 'scopedLocale',
            'localeValuesForNewFragment',
        ]);

        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function render()
    {
        return view('chief-fragments::livewire.add-fragment');
    }

    public function getAllowedFragmentsGrouped(): Collection
    {
        return $this->getAllowedFragments()
            ->groupBy(fn (Fragment $fragment) => $fragment->getCategory())
            ->sortDesc();
    }

    public function getAllowedFragments(): Collection
    {
        if ($this->parentId) {
            $fragment = app(FragmentRepository::class)->find($this->parentId);
            $allowedFragments = $fragment->allowedFragments();
        } else {
            $owner = app(ContextOwnerRepository::class)->findOwner($this->context->id);
            $allowedFragments = $owner->allowedFragments();
        }

        return collect($allowedFragments)
            ->map(fn ($fragmentClass) => app($fragmentClass));
    }

    public function attachFragment(string $fragmentId)
    {
        $order = $this->insertAfterOrder + 1;

        app(AttachFragment::class)->handle(
            $this->context->id,
            $fragmentId,
            $this->parentId,
            $order,
        );

        $eventPayload = [
            'fragmentId' => $fragmentId,
            'contextId' => $this->context->id,
            'parentId' => $this->parentId,
            'order' => $order,
        ];

        if ($this->parentId) {
            $this->dispatch('fragment-added-'.$this->parentComponentId, ...$eventPayload)->to('chief-fragments::edit-fragment');
        } else {
            $this->dispatch('fragment-added-'.$this->parentComponentId, ...$eventPayload)->to('chief-fragments::context');
        }

        $this->close();
    }
}
