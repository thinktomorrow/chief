<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\Actions\SyncLocales;

class Contexts extends Component
{
    public string $resourceKey;

    public string $modelReference;

    public function mount(string $resourceKey, ReferableModel&ContextOwner $model)
    {
        $this->resourceKey = $resourceKey;
        $this->modelReference = $model->modelReference()->get();
    }

    /** @return Collection<ContextDto> */
    public function getContexts(): Collection
    {
        return app(ComposeLivewireDto::class)
            ->getContextsByOwner(ModelReference::fromString($this->modelReference));
    }

    //    public function getListeners()
    //    {
    //        return [
    //            'fragment-updated' => 'onFragmentUpdated',
    //        ];
    //    }
    //
    //    public function onFragmentUpdated()
    //    {
    //        dd('sisi');
    //        // We are gonna update all the fragment previews in the contexts
    //    }
    //
    //    //    public function refreshSection(string $fragmentId): void
    //    //    {
    //    //        $this->dispatch('fragment-refresh-'.$this->getId(), $fragmentId);
    //    //    }

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
        $this->warningMessage = 'Opgelet! Als u <strong>'.$this->getRemovedLocalesAsString().'</strong> verwijdert, zullen ook de links en inhoud worden verwijderd van deze pagina.';
    }

    public function close()
    {
        $this->reset(['warningMessage', 'showConfirmButton', 'isSaving']);
        $this->activeLocales = $this->currentLocales;
        $this->isOpen = false;
    }

    public function render()
    {
        return view('chief-fragments::livewire.contexts');
    }
}
