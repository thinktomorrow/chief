<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\PageState;

use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelArchived;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelPublished;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelQueuedForDeletion;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUnPublished;
use Thinktomorrow\Chief\ManagedModels\States\State\State;
use Thinktomorrow\Chief\ManagedModels\States\State\StateAdminConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StateAdminConfigDefaults;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Site\Urls\UrlHelper;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class PageStateConfig implements StateAdminConfig
{
    use StateAdminConfigDefaults;

    public function getStateKey(): string
    {
        return PageState::KEY;
    }

    public function getStates(): array
    {
        return [
            PageState::draft,
            PageState::archived,
            PageState::deleted,
            PageState::published,
        ];
    }

    public function getTransitions(): array
    {
        return [
            'publish' => [
                'from' => [PageState::draft],
                'to' => PageState::published,
            ],
            'unpublish' => [
                'from' => [PageState::published],
                'to' => PageState::draft,
            ],
            'archive' => [
                'from' => [PageState::published, PageState::draft],
                'to' => PageState::archived,
            ],
            'unarchive' => [
                'from' => [PageState::archived],
                'to' => PageState::draft,
            ],
            'delete' => [
                'from' => [PageState::archived, PageState::draft],
                'to' => PageState::deleted,
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

        if ($transition == 'archive') {
            event(
                new ManagedModelArchived(
                    $statefulContract->modelReference(),
                    isset($data['redirect_id']) ? ModelReference::fromString($data['redirect_id']) : null
                )
            );

            Audit::activity()->performedOn($statefulContract)->log('archived');
        }

        if ($transition == 'unarchive') {
            Audit::activity()->performedOn($statefulContract)->log('unarchived');
        }

        if ($transition == 'delete') {
            event(new ManagedModelQueuedForDeletion($statefulContract->modelReference()));
            Audit::activity()->performedOn($statefulContract)->log('deleted');
        }
    }

    public function getEditTitle(StatefulContract $statefulContract): string
    {
        return 'Publicatie';
    }

    public function getStateLabel(StatefulContract $statefulContract): ?string
    {
        $stateLabel = match ($statefulContract->getState($this->getStateKey())) {
            PageState::published => 'Gepubliceerd',
            PageState::draft => 'Draft',
            PageState::archived => 'Gearchiveerd',
            PageState::deleted => 'Verwijderd',
            default => $statefulContract->getState($this->getStateKey())->getValueAsString(),
        };

        if ($this->visitableModelDoesNotHaveOnlineLinks($statefulContract)) {
            $stateLabel .= ' (link ontbreekt)';
        }

        return $stateLabel;
    }

    public function getStateVariant(StatefulContract $statefulContract): string
    {
        return $this->getVariantForState($statefulContract->getState($this->getStateKey()));
    }

    private function getVariantForState(State $state): string
    {
        return match ($state) {
            PageState::published => 'outline-green',
            PageState::draft => 'outline-blue',
            PageState::archived => 'outline-orange',
            PageState::deleted => 'outline-red',
            default => 'outline-blue',
        };
    }

    public function getEditContent(StatefulContract $statefulContract): ?string
    {
        $state = $statefulContract->getState($this->getStateKey());

        if ($state == PageState::draft) {
            return '<p>De pagina staat nog in draft.</p>';
        }

        if ($state == PageState::published) {
            return '<p>De pagina is gepubliceerd. 👍</p>';
        }

        return null;
    }

    public function getTransitionLabel(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        switch ($transitionKey) {
            case 'publish':
                return 'Publiceer';

            case 'unpublish':
                return 'Zet terug in draft';

            case 'archive':
                return 'Archiveer';

            case 'unarchive':
                return 'Haal uit archief';

            case 'delete':
                return 'Verwijder';

            default:
                return $transitionKey;
        }
    }

    public function getTransitionType(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        return match ($transitionKey) {
            'publish' => 'outline-blue',
            'unpublish' => 'outline-blue',
            'archive' => 'outline-orange',
            'unarchive' => 'outline-orange',
            'delete' => 'outline-red',
            default => 'outline-blue',
        };
    }

    public function getTransitionTitle(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        switch ($transitionKey) {
            case 'publish':
                return 'Publiceer deze pagina';

            case 'unpublish':
                return 'Hall pagina offline';

            case 'archive':
                return 'Archiveer';

            case 'unarchive':
                return 'Haal uit archief';

            case 'delete':
                return 'Verwijder';

            default:
                return $transitionKey;
        }
    }

    public function getTransitionContent(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        switch ($transitionKey) {
            case 'publish':
                return 'Publiceer deze pagina';

            case 'unpublish':
                return 'Hall pagina offline';

            case 'archive':
                return 'Archiveer';

            case 'unarchive':
                return 'Haal uit archief';

            case 'delete':
                return 'Opgelet! Het verwijderen van een pagina is definitief en kan niet worden ongedaan gemaakt.';

            default:
                return null;
        }
    }

    public function hasConfirmationForTransition(string $transitionKey): bool
    {
        if (in_array($transitionKey, ['archive', 'delete'])) {
            return true;
        }

        return false;
    }

    public function getRedirectAfterTransition(string $transitionKey, StatefulContract $statefulContract): ?string
    {
        if (in_array($transitionKey, ['archive', 'unarchive', 'delete'])) {
            return app(Registry::class)->findManagerByModel($statefulContract::class)->route('index');
        }

        return null;
    }

    public function getResponseNotification(string $transitionKey): ?string
    {
        if ($transitionKey == 'publish') {
            return 'De pagina is gepubliceerd.';
        }

        if ($transitionKey == 'archive') {
            return 'De pagina is gearchiveerd.';
        }

        if ($transitionKey == 'unarchive') {
            return 'De pagina is uit het archief gehaald.';
        }

        if ($transitionKey == 'delete') {
            return 'De pagina is definitief verwijderd.';
        }

        return null;
    }

    public function getConfirmationContent(string $transitionKey, StatefulContract $statefulContract): ?string
    {
        if ($transitionKey == 'archive') {
            $manager = app(Registry::class)->findManagerByModel($statefulContract::class);
            $resource = app(Registry::class)->findResourceByModel($statefulContract::class);

            return view('chief-states::archive-confirmation', [
                'manager' => $manager,
                'model' => $statefulContract,
                'resource' => $resource,
                'stateConfig' => $statefulContract->getStateConfig(PageState::KEY),
                'targetModels' => UrlHelper::allOnlineModels(false, $statefulContract),
            ])->render();
        }

        return null;
    }

    public function getAsyncModalUrl(string $transitionKey, StatefulContract $statefulContract): ?string
    {
        if ($transitionKey == 'archive') {
            return app(Registry::class)->findManagerByModel($statefulContract::class)->route('archive_modal', $statefulContract->id);
        }

        return null;
    }

    private function visitableModelDoesNotHaveOnlineLinks(StatefulContract $statefulContract): bool
    {
        if (! $statefulContract instanceof Visitable) {
            return false;
        }

        return $statefulContract->urls->isEmpty();
    }
}
