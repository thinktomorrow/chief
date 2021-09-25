<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\Admin\AdminConfig;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\ManagedModels\States\WithPageState;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\Chief\Site\Urls\Form\LinkForm;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

trait ManagedModelDefaults
{
    use SavingFields;
    use ReferableModelDefault;

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

    public function onlineStatusAsLabel(): string
    {
        if (! $this instanceof WithPageState) {
            return '';
        }

        if ($this instanceof Visitable) {
            if (LinkForm::fromModel($this)->isAnyLinkOnline()) {
                return '<span class="text-sm label label-success">Online</span>';
            } elseif ($this->getPageState() === PageState::PUBLISHED) {
                return '<span class="text-sm label label-info">Nog niet online. Er ontbreekt nog een link.</span>';
            } elseif ($this->getPageState() === PageState::DRAFT) {
                return '<span class="text-sm label label-error">Offline</span>';
            }
        }

        if ($this->getPageState() === PageState::PUBLISHED) {
            return '<span class="text-sm label label-success">Gepubliceerd</span>';
        }

        if ($this->getPageState() === PageState::DRAFT) {
            return '<span class="text-sm label label-error">In draft</span>';
        }

        if ($this->getPageState() === PageState::ARCHIVED) {
            return '<span class="text-sm label label-warning">Gearchiveerd</span>';
        }

        return '';
    }
}
