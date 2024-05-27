<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire;

use Illuminate\Support\Arr;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Sites\Actions\SyncLocales;

class ResourceSites extends Component
{
    use ShowsAsDialog;

    public string $resourceKey;
    public array $activeLocales = [];
    public array $currentLocales = [];
    public string $modelReference;
    public bool $showConfirmButton = false;
    public $warningMessage;
    public bool $isSaving = false;

    public function mount(string $resourceKey, ModelReference $modelReference, array $locales)
    {
        $this->resourceKey = $resourceKey;
        $this->modelReference = $modelReference->get();
        $this->activeLocales = $this->currentLocales = $locales;
    }

    public function submit()
    {
        if ($this->shouldConfirmFirst()) {
            $this->isSaving = false;
            $this->askConfirmation();

            return;
        }

        // Small timed delay to make it look like a big deal and we can see the spinner
        usleep(400 * 1000);

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

    public function getRemovedLocalesAsString(): string
    {
        return Arr::join(Arr::map($this->getRemovedLocales(), fn ($locale) => strtoupper($locale)), ', ', ' en ');
    }

    public function render()
    {
        return view('chief-locale::model-locales', [
            //
        ]);
    }
}
