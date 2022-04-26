<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\State;

interface StateConfig
{
    public function getStateKey(): string;
    public function getStates(): array;
    public function getTransitions(): array;
    public function emitEvent(StatefulContract $statefulContract, string $transition): void;

    public function getStateLabel(string $state): ?string;
    public function getStateContent(string $state): ?string;

    // Transition setup
    public function getTransitionLabel(string $transitionKey): ?string;
    public function getTransitionLabelType(string $transitionKey): ?string;
    public function getTransitionDescription(string $transitionKey): ?string;
    public function hasConfirmationForTransition(string $transitionKey): bool;
    public function getRedirectAfterTransition(string $transitionKey): ?string;
}
