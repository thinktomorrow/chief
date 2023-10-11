<?php

namespace Thinktomorrow\Chief\Locale\App\Livewire;

use Illuminate\Support\Arr;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Locale\Actions\SyncLocales;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class FragmentLocales extends Component
{
    use ShowsAsDialog;

    public array $activeLocales = [];
    public array $currentLocales = [];
    public string $ownerModelReference;
    public string $fragmentModelId;

    public function mount(ModelReference $ownerModelReference, string $fragmentModelId, array $locales)
    {
        $this->ownerModelReference = $ownerModelReference->get();
        $this->fragmentModelId = $fragmentModelId;
        $this->activeLocales = $this->currentLocales = $locales;
    }

    public function submit()
    {
        dd('submitting');

        // Get context

        // Get

        app(SyncLocales::class)->handle(
            $model = ModelReference::fromString($this->ownerModelReference)->instance(),
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
        $this->warningMessage = 'Opgelet! Als u de talen <strong>' . implode(',', $this->getRemovedLocales()) . '</strong> verwijderd, zullen ook de links en inhoud worden verwijderd van deze pagina.';
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
