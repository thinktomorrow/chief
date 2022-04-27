<?php

namespace Thinktomorrow\Chief\ManagedModels\States\State;

interface StateAdminConfig
{
    public function getWindowTitle(StatefulContract $statefulContract): string;
    public function getWindowContent(StatefulContract $statefulContract, array $viewData): string;

    public function getStateLabel(StatefulContract $statefulContract): ?string;
    public function getEditContent(StatefulContract $statefulContract): ?string;

    public function getTransitionLabel(string $transitionKey): ?string;
    public function getTransitionLabelType(string $transitionKey): ?string;

    public function getTransitionContent(string $transitionKey): ?string;

    public function hasConfirmationForTransition(string $transitionKey): bool;

    public function getAsyncModalUrl(string $transitionKey, StatefulContract $statefulContract): ?string;

    public function getRedirectAfterTransition(string $transitionKey, StatefulContract $statefulContract): ?string;
}
