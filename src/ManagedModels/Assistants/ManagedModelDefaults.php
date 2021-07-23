<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\Admin\AdminConfig;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\ManagedModels\States\WithPageState;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;

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

        if ($this->stateOf(PageState::KEY) === PageState::PUBLISHED) {
            return '<span class="label label-success text-sm">Online</span>';
        }

        if ($this->stateOf(PageState::KEY) === PageState::DRAFT) {
            return '<span class="label label-error text-sm">Offline</span>';
        }

        if ($this->stateOf(PageState::KEY) === PageState::ARCHIVED) {
            return '<span class="label label-warning text-sm">Gearchiveerd</span>';
        }

        return '';
    }
}
