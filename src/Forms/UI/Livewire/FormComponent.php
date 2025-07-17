<?php

namespace Thinktomorrow\Chief\Forms\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Forms\Layouts\PageLayout;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSites;

class FormComponent extends Component
{
    use WithMemoizedModel;

    public ModelReference $modelReference;

    public Form $form;

    public string $scopedLocale;

    public function mount(ReferableModel $model, \Thinktomorrow\Chief\Forms\Layouts\Form $form)
    {
        $this->modelReference = $model->modelReference();
        $this->form = $form;

        $this->scopedLocale = ChiefSites::getLocaleScope();

        $this->setMemoizedModel($model);
    }

    public function getListeners()
    {
        return [
            'form-updated-'.$this->getId() => 'onFormUpdated',
            'scoped-to-locale' => 'onScopedToLocale',
        ];
    }

    /**
     * Compose the form again so we get all the closures
     * and such of all fields and layouts
     */
    public function getComponents(): array
    {
        $model = $this->getModel();
        $resource = app(Registry::class)->findResourceByModel($model::class);

        return PageLayout::make($resource->fields($model))
            ->findForm($this->form->getId())
            ->model($model)
            ->setScopedLocale($this->scopedLocale)
            ->getComponents();
    }

    public function editForm(): void
    {
        $model = $this->getModel();
        $locales = $model instanceof HasAllowedSites ? ChiefSites::verifiedLocales($model->getAllowedSites()) : ChiefSites::locales();

        $this->dispatch('open-'.$this->getId(), [
            'locales' => $locales,
            'scopedLocale' => $this->scopedLocale,
        ])->to('chief-wire::edit-form');
    }

    public function onFormUpdated(): void
    {
        // Refresh is done automatically by livewire when this method is called
    }

    public function onScopedToLocale($locale): void
    {
        $this->scopedLocale = $locale;
    }

    public function render()
    {
        if ($this->form->getFormDisplay() == 'inline') {
            return view('chief-form::livewire.form-inline');
        }

        if ($this->form->getFormDisplay() == 'compact') {
            return view('chief-form::livewire.form-compact');
        }

        return view('chief-form::livewire.form');
    }
}
