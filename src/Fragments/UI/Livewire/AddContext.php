<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\CreateContext;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Sites\ChiefSites;

class AddContext extends Component
{
    use HasForm;
    use ShowsAsDialog;

    public string $modelReference;

    public array $modelLocales;

    public function mount(string $modelReference, array $modelLocales)
    {
        $this->modelReference = $modelReference;

        $this->modelLocales = $modelLocales;
    }

    public function getListeners()
    {
        return [
            'open-add-context' => 'open',
        ];
    }

    public function open($values = [])
    {
        $this->isOpen = true;

        $this->form['locales'] = ChiefSites::locales();
        $this->form['active_sites'] = [];
    }

    public function close()
    {
        $this->reset(['form']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function save()
    {
        $this->validate([
            'form.locales' => 'required|array|min:1',
            'form.title' => 'required',
        ], [
            'form.locales.required' => 'Voeg minstens één taal toe. Dit bepaalt in welke talen je de fragmenten moet invullen.',
            'form.title.required' => 'Voorzie nog voor jezelf een titel. Kort en bondig.',
        ]);

        $contextId = app(ContextApplication::class)->create(new CreateContext(
            ModelReference::fromString($this->modelReference),
            $this->form['locales'] ?? [],
            [], // No active sites on adding new context
            $this->form['title'] ?? null
        ));

        $this->dispatch($this->modelReference.'-contexts-updated', ...[
            'contextId' => $contextId,
        ]);

        $this->close();
    }

    public function render()
    {
        return view('chief-fragments::livewire.add-context');
    }

    public function getAvailableLocales(): array
    {
        return ChiefSites::all()->filterByLocales($this->modelLocales)->toCollection()->pluck('shortName', 'locale')->all();
    }
}
