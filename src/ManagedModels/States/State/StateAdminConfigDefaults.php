<?php

namespace Thinktomorrow\Chief\ManagedModels\States\State;

use Illuminate\Support\Str;

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

    public function getEditTitle(StatefulContract $statefulContract): string
    {
        return 'Status';
    }

    public function getEditContent(StatefulContract $statefulContract): ?string
    {
        return null;
    }

    public function getTransitionLabel(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        return Str::replace('_', ' ', $transitionKey);
    }

    public function getTransitionType(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        return null;
    }

    public function getTransitionTitle(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        return null;
    }

    public function getTransitionContent(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        return null;
    }

    public function getTransitionFields(string $transitionKey, StatefulContract $statefulContract): iterable
    {
        return [];
    }

    public function hasConfirmationForTransition(string $transitionKey): bool
    {
        return false;
    }

    public function getConfirmationContent(string $transitionKey, StatefulContract $statefulContract): ?string
    {
        return null;
    }

    public function getAsyncModalUrl(string $transitionKey, StatefulContract $statefulContract): ?string
    {
        return null;
    }

    public function getRedirectAfterTransition(string $transitionKey, StatefulContract $statefulContract): ?string
    {
        return null;
    }

    public function getResponseNotification(string $transitionKey): ?string
    {
        return null;
    }
}
