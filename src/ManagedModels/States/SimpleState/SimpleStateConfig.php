<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\SimpleState;

use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelPublished;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelQueuedForDeletion;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUnPublished;
use Thinktomorrow\Chief\ManagedModels\States\State\StateAdminConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StateAdminConfigDefaults;
use Thinktomorrow\Chief\ManagedModels\States\State\StateConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Managers\Register\Registry;

class SimpleStateConfig implements StateConfig, StateAdminConfig
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
        if ('publish' == $transition) {
            event(new ManagedModelPublished($statefulContract->modelReference()));
            Audit::activity()->performedOn($statefulContract)->log('published');
        }

        if ('unpublish' == $transition) {
            event(new ManagedModelUnPublished($statefulContract->modelReference()));
            Audit::activity()->performedOn($statefulContract)->log('unpublished');
        }

        if ('delete' == $transition) {
            event(new ManagedModelQueuedForDeletion($statefulContract->modelReference()));
            Audit::activity()->performedOn($statefulContract)->log('deleted');
        }
    }

    public function getWindowTitle(StatefulContract $statefulContract): string
    {
        return 'Status';
    }

    public function getStateLabel(StatefulContract $statefulContract): ?string
    {
        switch ($statefulContract->getState($this->getStateKey())) {

            case SimpleState::online:
                return '<span class="label label-xs label-success">Online</span>';

            case SimpleState::offline:
                return '<span class="label label-xs label-error">Offline</span>';

            case SimpleState::deleted:
                return '<span class="label label-xs label-grey">Verwijderd</span>';

            default:
                return $statefulContract->getState($this->getStateKey())?->getValueAsString();
        }
    }

    public function getTransitionButtonLabel(string $transitionKey): ?string
    {
        switch ($transitionKey) {
            case 'publish':
                return 'Zet online';

            case 'unpublish':
                return 'Haal offline';

            case 'delete':
                return 'verwijder';

            default:
                return $transitionKey;
        }
    }

    public function getTransitionType(string $transitionKey): ?string
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

    public function getTransitionContent(string $transitionKey): ?string
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

    public function getRedirectAfterTransition(string $transitionKey, StatefulContract $statefulContract): ?string
    {
        if (in_array($transitionKey, ['delete'])) {
            return app(Registry::class)->findManagerByModel($statefulContract::class)->route('index');
        }

        return null;
    }
}
