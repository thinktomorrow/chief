<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\SimpleState;

use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelPublished;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelQueuedForDeletion;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUnPublished;
use Thinktomorrow\Chief\ManagedModels\States\State\State;
use Thinktomorrow\Chief\ManagedModels\States\State\StateAdminConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StateAdminConfigDefaults;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Managers\Register\Registry;

class SimpleStateConfig implements StateAdminConfig
{
    use StateAdminConfigDefaults;

    public function getStateKey(): string
    {
        return SimpleState::KEY;
    }

    public function getStates(): array
    {
        return [
            SimpleState::online,
            SimpleState::offline,
            SimpleState::deleted,
        ];
    }

    public function getTransitions(): array
    {
        return [
            'publish' => [
                'from' => [SimpleState::offline],
                'to' => SimpleState::online,
            ],
            'unpublish' => [
                'from' => [SimpleState::online],
                'to' => SimpleState::offline,
            ],
            'delete' => [
                'from' => [SimpleState::online, SimpleState::offline],
                'to' => SimpleState::deleted,
            ],
        ];
    }

    public function emitEvent(StatefulContract $statefulContract, string $transition, array $data): void
    {
        if ($transition == 'publish') {
            event(new ManagedModelPublished($statefulContract->modelReference()));
            Audit::activity()->performedOn($statefulContract)->log('published');
        }

        if ($transition == 'unpublish') {
            event(new ManagedModelUnPublished($statefulContract->modelReference()));
            Audit::activity()->performedOn($statefulContract)->log('unpublished');
        }

        if ($transition == 'delete') {
            event(new ManagedModelQueuedForDeletion($statefulContract->modelReference()));
            Audit::activity()->performedOn($statefulContract)->log('deleted');
        }
    }

    public function getEditTitle(StatefulContract $statefulContract): string
    {
        return 'Status';
    }

    public function getStateLabel(StatefulContract $statefulContract): ?string
    {
        return match ($statefulContract->getState($this->getStateKey())) {
            SimpleState::online => 'Gepubliceerd',
            SimpleState::offline => 'Draft',
            SimpleState::deleted => 'Verwijderd',
            default => $statefulContract->getState($this->getStateKey())?->getValueAsString(),
        };
    }

    public function getStateVariant(StatefulContract $statefulContract): string
    {
        return $this->getVariantForState($statefulContract->getState($this->getStateKey()));
    }

    private function getVariantForState(State $state): string
    {
        return match ($state) {
            SimpleState::online => 'outline-blue',
            SimpleState::offline => 'outline-grey',
            SimpleState::deleted => 'outline-red',
            default => 'outline-blue',
        };
    }

    public function getTransitionType(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        return match ($transitionKey) {
            'publish' => 'success',
            'delete' => 'error',
            default => 'info',
        };
    }

    public function getTransitionTitle(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        return match ($transitionKey) {
            'publish' => 'Online zetten',
            'unpublish' => 'Offline zetten',
            'delete' => 'Verwijderen',
            default => $transitionKey,
        };
    }

    public function getTransitionContent(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        return match ($transitionKey) {
            'publish' => 'Het item zal onmiddellijk online komen te staan en zichtbaar zijn op de website.',
            'unpublish' => 'Het item zal onmiddellijk offline worden gehaald en niet meer zichtbaar zijn op de website.',
            'delete' => 'Opgelet! Het verwijderen van dit item is definitief en kan niet worden ongedaan gemaakt.',
            default => null,
        };
    }

    public function getTransitionLabel(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        return match ($transitionKey) {
            'publish' => 'Zet online',
            'unpublish' => 'Zet offline',
            'delete' => 'Verwijder',
            default => $transitionKey,
        };
    }

    public function hasConfirmationForTransition(string $transitionKey): bool
    {
        if (in_array($transitionKey, ['delete'])) {
            return true;
        }

        return false;
    }

    public function getRedirectAfterTransition(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        if (in_array($transitionKey, ['delete'])) {
            return app(Registry::class)->findManagerByModel($statefulContract::class)->route('index');
        }

        return null;
    }
}
