<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\Admin\AdminConfig;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ProvidesUrl;

trait ManagedModelDefaults
{
    use SavingFields;

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
        if (! $this instanceof StatefulContract) {
            return '';
        }

        // if ($this->stateOf(PageState::KEY) === PageState::PUBLISHED) {
        //     return $this instanceof ProvidesUrl
        //         ? '<a href="' . $this->url() . '" target="_blank"><em>online</em></a>'
        //         : '<span><em>online</em></span>';
        // }

        // if ($this->stateOf(PageState::KEY) === PageState::DRAFT) {
        //     return $this instanceof ProvidesUrl
        //         ? '<a href="' . $this->url() . '" target="_blank" class="text-error"><em>offline</em></a>'
        //         : '<span class="text-error"><em>offline</em></span>';
        // }

        // if ($this->stateOf(PageState::KEY) === PageState::ARCHIVED) {
        //     return '<span><em>gearchiveerd</em></span>';
        // }

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
