<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;
use Thinktomorrow\Chief\Fragments\App\ContextActions\SaveContexts;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Sites\Locales\ChiefLocales;

class EditContexts extends Component
{
    use HasForm;
    use ShowsAsDialog;

    public string $modelReference;

    public Collection $contexts;

    public function mount(string $modelReference)
    {
        $this->modelReference = $modelReference;
    }

    public function getListeners()
    {
        return [
            'open-edit-contexts' => 'open',
        ];
    }

    public function open($values = [])
    {
        $this->contexts = $this->getContexts();

        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->initialFormValues();

        $this->isOpen = true;
    }

    public function close()
    {
        $this->reset(['form', 'contexts']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    private function getContexts(): Collection
    {
        return app(ComposeLivewireDto::class)
            ->getContextsByOwner(ModelReference::fromString($this->modelReference));
    }

    public function addContext(): void
    {
        $this->contexts->push(
            app(ComposeLivewireDto::class)
                ->composeEmptyContext(ModelReference::fromString($this->modelReference))
        );

        $this->initialFormValues();
    }

    public function deleteContext(string $id): void
    {
        $this->form[$id] = null;
    }

    public function undoDeleteContext(string $id): void
    {
        $this->initialFormValues();
    }

    public function save()
    {
        //        $this->validate([
        //            'form.*.slug' => 'required',
        //            'form.*.status' => 'required',
        //        ]);
        //        dd($this->form);
        app(SaveContexts::class)->handle(ModelReference::fromString($this->modelReference), $this->form);

        $this->dispatch($this->modelReference.'-contexts-updated');

        $this->close();
    }

    public function render()
    {
        return view('chief-fragments::livewire.edit-contexts');
    }

    public function queuedForDeletion(string $locale): bool
    {
        return ! isset($this->form[$locale]) || ! $this->form[$locale];
    }

    public function getAvailableLocales(): array
    {
        return PairOptions::toPairs(ChiefLocales::locales());
    }

    private function initialFormValues()
    {
        foreach ($this->contexts as $context) {
            if (isset($this->form[$context->id])) {
                continue;
            }

            $this->form[$context->id] = [
                'title' => $context->title,
                'locales' => $context->locales,
            ];
        }
    }
}
