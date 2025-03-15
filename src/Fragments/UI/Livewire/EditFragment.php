<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Forms;
use Thinktomorrow\Chief\Fragments\App\Actions\IsolateFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\PutFragmentOffline;
use Thinktomorrow\Chief\Fragments\App\Actions\PutFragmentOnline;
use Thinktomorrow\Chief\Fragments\App\Actions\ReorderFragments;
use Thinktomorrow\Chief\Fragments\App\Actions\UpdateFragment;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;

class EditFragment extends Component
{
    use HasForm;
    use InteractsWithFields;
    use ShowsAsDialog;

    // parent livewire component id
    public string $parentComponentId;

    public string $contextId;

    public ?FragmentDto $fragment = null;

    public function mount(string $contextId, ?string $parentComponentId = null)
    {
        $this->contextId = $contextId;
        $this->parentComponentId = $parentComponentId;
    }

    public function getListeners()
    {
        return [
            'open-'.$this->parentComponentId => 'open',
            'files-updated' => 'onfilesUpdated',
            'fragment-added' => 'onFragmentAdded',
            'request-refresh' => '$refresh',
        ];
    }

    public function open($values = [])
    {
        $this->fragment = FragmentDto::fromLivewire($values['fragment']);

        if ($this->fragment->isDeleted()) {
            throw new \InvalidArgumentException('Fragment ['.$this->fragment->fragmentId.'] has been deleted');
        }

        $this->isOpen = true;

        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->injectFormValues($this->getFields());

        $this->dispatch('fragment-dialog-opened', ...[
            'componentId' => $this->getId(),
            'parentComponentId' => $this->parentComponentId,
            'contextId' => $this->fragment->contextId,
            'fragmentId' => $this->fragment->fragmentId,
        ]);
    }

    public function close()
    {
        $this->reset(['fragment', 'form']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function getFields(): Collection
    {
        $forms = Forms::make($this->fragment->fields)
            ->fillModel($this->fragment->getFragmentModel())
            ->get();

        return collect($forms)->map(fn ($form) => $form->getComponents())->flatten();
    }

    /** @return Collection<FragmentDto> */
    public function getFragments(): Collection
    {
        $fragmentCollection = app(FragmentRepository::class)->getFragmentCollection($this->fragment->contextId, $this->fragment->fragmentId);

        return collect($fragmentCollection->all())
            ->map(fn ($fragment) => FragmentDto::fromFragment($fragment, $this->fragment->context));
    }

    public function addFragment(int $order): void
    {
        $this->dispatch('open-'.$this->getId(), [
            'order' => $order,
            'parentId' => $this->fragment->fragmentId,
        ])->to('chief-fragments::add-fragment');
    }

    public function deleteFragment(): void
    {
        $this->dispatch('fragment-deleting', ...[
            'fragmentId' => $this->fragment->fragmentId,
            'contextId' => $this->fragment->contextId,
            'parentId' => $this->fragment->parentId,
            'componentId' => $this->getId(),
            'parentComponentId' => $this->parentComponentId,
        ]);

        $this->close();
    }

    public function isolateFragment(): void
    {
        $isolatedFragmentId = app(IsolateFragment::class)->handle($this->fragment->contextId, $this->fragment->fragmentId);

        $this->dispatch('fragment-isolated', ...[
            'fragmentId' => $isolatedFragmentId,
            'formerFragmentId' => $this->fragment->fragmentId,
            'contextId' => $this->fragment->contextId,
            'parentComponentId' => $this->parentComponentId,
        ]);

        $this->close();
    }

    public function putOnline(): void
    {
        app(PutFragmentOnline::class)->handle($this->fragment->fragmentId);

        $this->fragment = $this->fragment->changeOnlineState(true);

        $this->dispatchUpdateEventAndClose();
    }

    public function putOffline(): void
    {
        app(PutFragmentOffline::class)->handle($this->fragment->fragmentId);

        $this->fragment = $this->fragment->changeOnlineState(false);

        $this->dispatchUpdateEventAndClose();
    }

    public function onFragmentAdded(string $fragmentId, string $contextId, ?string $parentId, int $order): void
    {
        if (! $parentId || $parentId !== $this->fragment->fragmentId) {
            return;
        }

        $this->dispatch('request-refresh')->self();

        $this->reorder($this->getFragmentIdsInOrder());
    }

    private function getFragmentIdsInOrder(): array
    {
        return $this->getFragments()
            ->map(fn (FragmentDto $fragment) => $fragment->fragmentId)
            ->all();
    }

    public function reorder($orderedIds)
    {
        app(ReorderFragments::class)->handle($this->fragment->contextId, $orderedIds, $this->fragment->fragmentId);
    }

    public function save()
    {
        // Validation is done via the update command
        app(UpdateFragment::class)->handle(
            $this->fragment->contextId,
            $this->fragment->fragmentId,
            $this->form,
            [],
        );

        $this->dispatchUpdateEventAndClose();
    }

    private function dispatchUpdateEventAndClose(): void
    {
        $this->dispatch('fragment-updated', ...[
            'fragmentId' => $this->fragment->fragmentId,
            'contextId' => $this->fragment->contextId,
            'parentComponentId' => $this->parentComponentId,
        ])->to('chief-fragments::context');

        $this->close();
    }

    public function render()
    {
        return view('chief-fragments::livewire.edit-fragment');
    }
}
