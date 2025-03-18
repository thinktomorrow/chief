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
use Thinktomorrow\Chief\Fragments\App\Actions\UpdateFragment;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\UI\Livewire\_partials\WithFragments;

class EditFragment extends Component
{
    use HasForm;
    use InteractsWithFields;
    use ShowsAsDialog;
    use WithFragments;

    // parent livewire component id
    public string $parentComponentId;

    public ContextDto $context;

    public ?FragmentDto $fragment = null;

    public function mount(ContextDto $context, string $parentComponentId)
    {
        $this->context = $context;
        $this->parentComponentId = $parentComponentId;
    }

    public function getListeners()
    {
        return array_merge(
            $this->getListenersWithFragments(),
            [
                'open-'.$this->parentComponentId => 'open',
                'request-refresh' => '$refresh',
            ]
        );
    }

    public function open($values = [])
    {
        $this->fragment = $this->composeFragmentDto($values['fragmentId']);

        // Load any child fragments
        $this->refreshFragments();

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

    private function composeFragmentDto(string $fragmentId): FragmentDto
    {
        return FragmentDto::fromFragment(
            app(FragmentRepository::class)->findInContext($fragmentId, $this->context->contextId),
            app(ComposeLivewireDto::class)->getContext($this->context->contextId)
        );
    }

    // TODO(ben): this also closes parent dialogs
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

    public function deleteFragment(): void
    {
        $this->dispatch('fragment-deleting-'.$this->parentComponentId, ...[
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

        $this->dispatch('fragment-isolated-'.$this->parentComponentId, ...[
            'fragmentId' => $isolatedFragmentId,
            'formerFragmentId' => $this->fragment->fragmentId,
            'contextId' => $this->fragment->contextId,
            'parentId' => $this->fragment->parentId,
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

    //    private function getFragmentIdsInOrder(): array
    //    {
    //        return $this->fragments
    //            ->map(fn (FragmentDto $fragment) => $fragment->fragmentId)
    //            ->all();
    //    }

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
        $this->dispatch('fragment-updated-'.$this->parentComponentId, ...[
            'fragmentId' => $this->fragment->fragmentId,
            'contextId' => $this->fragment->contextId,
            'parentId' => $this->fragment->parentId,
            'parentComponentId' => $this->parentComponentId,
        ]);

        $this->close();
    }

    public function render()
    {
        return view('chief-fragments::livewire.edit-fragment');
    }
}
