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

    public function getStateVariant(StatefulContract $statefulContract): string
    {
        return 'outline-blue';
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

    public function hasConfirmationForTransition(string $transitionKey): bool
    {
        return false;
    }

    public function getConfirmationLabel(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        return $this->getTransitionLabel($statefulContract, $transitionKey);
    }

    public function getConfirmationTitle(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        return $this->getTransitionTitle($statefulContract, $transitionKey);
    }

    public function getConfirmationContent(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        return null;
    }

    public function getConfirmationFields(StatefulContract $statefulContract, string $transitionKey): iterable
    {
        return [];
    }

    public function getAsyncModalUrl(string $transitionKey, StatefulContract $statefulContract): ?string
    {
        return null;
    }

    public function getRedirectAfterTransition(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        return null;
    }

    public function getResponseNotification(string $transitionKey): ?string
    {
        return null;
    }
}
