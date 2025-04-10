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
        switch ($statefulContract->getState($this->getStateKey())) {
            case SimpleState::online:
                return 'Gepubliceerd';

            case SimpleState::offline:
                return 'Draft';

            case SimpleState::deleted:
                return 'Verwijderd';

            default:
                return $statefulContract->getState($this->getStateKey())?->getValueAsString();
        }
    }

    public function getTransitionLabel(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        switch ($transitionKey) {
            case 'publish':
                return 'Publiceer';

            case 'unpublish':
                return 'Zet in draft';

            case 'delete':
                return 'Verwijder';

            default:
                return $transitionKey;
        }
    }

    public function getStateVariant(StatefulContract $statefulContract): string
    {
        return $this->getVariantForState($statefulContract->getState($this->getStateKey()));
    }

    private function getVariantForState(State $state): string
    {
        return match ($state) {
            SimpleState::online => 'outline-blue',
            SimpleState::offline => 'outline-orange',
            SimpleState::deleted => 'outline-red',
            default => 'outline-blue',
        };
    }

    public function getTransitionType(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        switch ($transitionKey) {
            case 'publish':
                return 'success';

            case 'delete':
                return 'error';

            default:
                return 'info';
        }
    }

    public function getTransitionContent(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        if ($transitionKey == 'delete') {
            return 'Opgelet! Het verwijderen is definitief. Dit kan niet worden ongedaan gemaakt.';
        }

        return null;
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
