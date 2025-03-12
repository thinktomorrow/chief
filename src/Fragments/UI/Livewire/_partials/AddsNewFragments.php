<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\_partials;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Forms;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentFactory;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\UI\Livewire\InteractsWithFields;

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
    }

    public function getFields(): Collection
    {
        $fragment = $this->getFragment();

        $forms = Forms::make($fragment->fields($fragment))
            // ->notTagged(['edit', 'not-on-create']) // TODO: make consistent tags...
            ->get();

        return collect($forms)->map(fn ($form) => $form->getComponents())->flatten();
    }

    public function save()
    {
        // Validation is done via create fragment command
        $fragmentId = app(CreateFragment::class)->handle(
            $this->fragmentKey,
            $this->form,
            [],
        );

        $this->addFragment($fragmentId);
    }

    /** Reference of fragment for create form */
    private function getFragment(): Fragment
    {
        return app(FragmentFactory::class)->createObject($this->fragmentKey);
    }
}
