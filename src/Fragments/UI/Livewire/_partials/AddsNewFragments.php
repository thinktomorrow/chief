<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\_partials;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Layouts\Layout;
use Thinktomorrow\Chief\Forms\UI\Livewire\InteractsWithFields;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentFactory;
use Thinktomorrow\Chief\Fragments\Fragment;

trait AddsNewFragments
{
    use HasForm;
    use InteractsWithFields;

    public bool $showCreate = false;

    public ?string $fragmentKey = null;

    public function showCreateForm(string $fragmentKey): void
    {
        $this->fragmentKey = $fragmentKey;

        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->injectFormValues($this->getFields());

        $this->showCreate = true;

        $this->dispatch('form-dialog-opened', ...[
            'componentId' => $this->getId(),
            'parentComponentId' => $this->parentComponentId,
            'contextId' => $this->context->id,
        ]);
    }

    public function getFields(): Collection
    {
        $fragment = $this->getFragment();

        $layout = Layout::make($fragment->fields($fragment))
            ->filterByNotTagged(['edit', 'not-on-create']) // TODO: make consistent tags...
            ->setScopedLocales($this->context->locales);

        return $layout->getComponentsWithoutForms();
    }

    public function save()
    {
        // Validation is done via create fragment command
        $fragmentId = app(CreateFragment::class)->handle(
            $this->fragmentKey,
            $this->context->locales,
            $this->form,
            [],
        );

        $this->attachFragment($fragmentId);
    }

    /** Reference of fragment for create form */
    private function getFragment(): Fragment
    {
        return app(FragmentFactory::class)->createObject($this->fragmentKey);
    }
}
