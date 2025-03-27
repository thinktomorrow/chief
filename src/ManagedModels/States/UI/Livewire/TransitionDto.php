<?php

namespace Thinktomorrow\Chief\ManagedModels\States\UI\Livewire;

use Thinktomorrow\Chief\ManagedModels\States\State\StateAdminConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

class TransitionDto
{
    public function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly string $variant,
        public readonly ?string $title,
        public readonly ?string $content,

        public readonly bool $hasConfirmation,
        public readonly ?string $confirmationLabel,
        public readonly ?string $confirmationTitle,
        public readonly ?string $confirmationContent,
        public readonly array $confirmationFields,
        public readonly ?string $redirectTo,
        public readonly ?string $redirectNotification,
    ) {}

    public static function fromConfig(StatefulContract $model, StateAdminConfig $config, $transitionKey): self
    {
        return new self(
            $transitionKey,
            $config->getTransitionLabel($model, $transitionKey),
            $config->getTransitionType($model, $transitionKey),
            $config->getTransitionTitle($model, $transitionKey),
            $config->getTransitionContent($model, $transitionKey),
            $config->hasConfirmationForTransition($transitionKey),
            $config->getConfirmationLabel($model, $transitionKey),
            $config->getConfirmationTitle($model, $transitionKey),
            $config->getConfirmationContent($model, $transitionKey),
            $config->getConfirmationFields($model, $transitionKey),
            $config->getRedirectAfterTransition($model, $transitionKey),
            $config->getResponseNotification($transitionKey),
        );
    }
}
