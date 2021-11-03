<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Admin\AdminConfig;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\ManagedModels\Fields\Field;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\ManagedModels\States\WithPageState;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\Chief\Site\Urls\Form\LinkForm;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

trait ManagedModelDefaults
{
    use SavingFields;
    use ReferableModelDefault;

    public function field(string $key): Field
    {
        $fieldModel = $this instanceof Fragmentable ? $this->fragmentModel() : $this;

        return Fields::make($this->fields())->find($key)->model($fieldModel);
    }

    public static function managedModelKey(): string
    {
        $shortClassName = (new \ReflectionClass(static::class))->getShortName();

        return Str::snake(Str::singular($shortClassName));
    }

    public function adminConfig(): AdminConfig
    {
        return AdminConfig::make()
            ->defaults($this);
    }

    public function adminView(): View
    {
        return view('chief::manager.edit');
    }

    public function onlineStatusAsLabel(): string
    {
        if (! $this instanceof WithPageState) {
            return '';
        }

        if ($this instanceof Visitable) {
            if (LinkForm::fromModel($this)->isAnyLinkOnline()) {
                return '<span class="label label-xs label-success">Online</span>';
            } elseif ($this->getPageState() === PageState::PUBLISHED) {
                return '<span class="label label-xs label-info">Nog niet online. Er ontbreekt nog een link.</span>';
            } elseif ($this->getPageState() === PageState::DRAFT) {
                return '<span class="label label-xs label-error">Offline</span>';
            }
        }

        if ($this->getPageState() === PageState::PUBLISHED) {
            return '<span class="label label-xs label-success">Gepubliceerd</span>';
        }

        if ($this->getPageState() === PageState::DRAFT) {
            return '<span class="label label-xs label-error">In draft</span>';
        }

        if ($this->getPageState() === PageState::ARCHIVED) {
            return '<span class="label label-xs label-warning">Gearchiveerd</span>';
        }

        return '';
    }
}
