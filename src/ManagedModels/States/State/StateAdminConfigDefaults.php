<?php

namespace Thinktomorrow\Chief\ManagedModels\States\State;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;

trait StateAdminConfigDefaults
{
    public function emitEvent(StatefulContract $statefulContract, string $transition, array $data): void
    {
        //
    }

    public function getStateLabel(StatefulContract $statefulContract): ?string
    {
        return $statefulContract->getState($this->getStateKey())->getValueAsString();
    }

    public function getWindowTitle(StatefulContract $statefulContract): string
    {
        return 'Status';
    }

    public function getWindowContent(StatefulContract $statefulContract, array $viewData): string
    {
        return Blade::render('<x-slot name="labels">' .
            $this->getStateLabel($statefulContract) .
            '</x-slot>');
    }

    public function getEditContent(StatefulContract $statefulContract): ?string
    {
        return null;
    }

    public function getTransitionButtonLabel(string $transitionKey): ?string
    {
        return Str::replace('_', ' ', $transitionKey);
    }

    public function getTransitionType(string $transitionKey): ?string
    {
        return null;
    }

    public function getTransitionContent(string $transitionKey): ?string
    {
        return null;
    }

    public function hasConfirmationForTransition(string $transitionKey): bool
    {
        return false;
    }

    public function getAsyncModalUrl(string $transitionKey, StatefulContract $statefulContract): ?string
    {
        return null;
    }

    public function getRedirectAfterTransition(string $transitionKey, StatefulContract $statefulContract): ?string
    {
        return null;
    }
}
