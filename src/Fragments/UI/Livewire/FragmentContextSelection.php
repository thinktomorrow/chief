<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Sites\Actions\SyncLocales;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class FragmentContextSelection extends Component
{
    public string $resourceKey;
    public array $contextIds = [];
    public string $modelReference;
    public string $currentContextId;

    public function mount(string $resourceKey, ModelReference $modelReference, string $currentContextId)
    {
        $this->resourceKey = $resourceKey;
        $this->modelReference = $modelReference->get();
        $this->currentContextId = $currentContextId;

        // Get other contextIds from owner based on the owner->getLocales()
        // Show contexts as locales
    }

    public function submit()
    {
        app(SyncLocales::class)->handle(
            $this->resourceKey,
            $model = ModelReference::fromString($this->modelReference)->instance(),
            $this->activeLocales,
        );

        $this->js('window.location.reload()');

        $this->close();
    }

    private function shouldConfirmFirst()
    {
        return ! $this->showConfirmButton && $this->isAboutToRemoveLocales();
    }

    public function isAboutToRemoveLocales(): bool
    {
        return count($this->getRemovedLocales()) > 0;
    }

    public function getRemovedLocales()
    {
        $removedLocales = [];

        foreach ($this->currentLocales as $locale) {
            if (! in_array($locale, $this->activeLocales)) {
                $removedLocales[] = $locale;
            }
        }

        return $removedLocales;
    }

    private function askConfirmation()
    {
        $this->showConfirmButton = true;
        $this->warningMessage = 'Opgelet! Als u <strong>' . $this->getRemovedLocalesAsString() . '</strong> verwijdert, zullen ook de links en inhoud worden verwijderd van deze pagina.';
    }

    public function close()
    {
        $this->reset(['warningMessage', 'showConfirmButton', 'isSaving']);
        $this->activeLocales = $this->currentLocales;
        $this->isOpen = false;
    }

    public function render()
    {
        return view('chief-fragments::components.fragment-context-selection', [
            //
        ]);
    }
}
