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
use Thinktomorrow\Chief\Site\Urls\LinkStatus;
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
        return 'Pas de pagina status aan';
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

        if ($this->visitableModelHasAnyLinks($statefulContract)) {
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
            PageState::published => 'outline-blue',
            PageState::draft => 'outline-white',
            PageState::archived => 'outline-orange',
            PageState::deleted => 'outline-red',
            default => 'outline-white',
        };
    }

    public function getEditContent(StatefulContract $statefulContract): ?string
    {
        return match ($statefulContract->getState($this->getStateKey())) {
            PageState::draft => '<p>De pagina staat momenteel in draft. Klik op publiceren om de pagina online te zetten.</p>',
            PageState::published => '<p>De pagina is momenteel gepubliceerd. Klik op offline halen om de pagina offline te zetten.</p>',
            PageState::archived => '<p>De pagina is momenteel gearchiveerd. Klik op herstellen om de pagina terug online te zetten.</p>',
            default => null,
        };
    }

    public function getTransitionLabel(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        switch ($transitionKey) {
            case 'publish':
                return 'Publiceer';

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
                return 'Publiceer';

            case 'unpublish':
                return 'Draft';

            case 'archive':
                return 'Archiveer';

            case 'unarchive':
                return 'Herstel';

            case 'delete':
                return 'Verwijder';

            default:
                return $transitionKey;
        }
    }

    public function getTransitionContent(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        return match ($transitionKey) {
            'publish' => $this->visitableModelHasAnyOnlineLinks($statefulContract)
                ? 'De pagina zal onmiddellijk online komen te staan.'
                : ($this->visitableModelHasAnyLinks($statefulContract)
                    ? 'Deze pagina heeft nog geen online links. Om de pagina zichtbaar te maken voor bezoekers, moet je ook nog de links online zetten.'
                    : 'Deze pagina heeft nog geen links. Om de pagina zichtbaar te maken voor bezoekers, moet je ook nog links toevoegen.'
                ),
            'unpublish' => $this->visitableModelHasAnyOnlineLinks($statefulContract)
                ? 'De pagina wordt offline gehaald en alle links zullen niet langer werken. Ze werken pas weer zodra de pagina opnieuw wordt gepubliceerd.'
                : 'De pagina is nog niet klaar voor publicatie en wordt terug in draft gezet.',
            'archive' => $this->visitableModelHasAnyOnlineLinks($statefulContract)
                ? 'Na het archiveren zullen alle links naar deze pagina niet meer werken. Zorg best voor een redirect naar een andere pagina zodat bezoekers altijd op een bestaande pagina terechtkomen.'
                : 'Hiermee verplaats je de pagina naar het archief. Alle pagina links zullen worden verwijderd. Je kan de pagina nadien nog herstellen vanuit het archief.',
            'delete' => 'Opgelet! Het verwijderen van een pagina is definitief en kan niet worden ongedaan gemaakt. Links zullen worden verwijderd.',
            default => null,
        };
    }

    public function hasConfirmationForTransition(string $transitionKey): bool
    {
        if (in_array($transitionKey, ['archive', 'delete'])) {
            return true;
        }

        return false;
    }

    public function getConfirmationContent(StatefulContract $statefulContract, string $transitionKey): ?string
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

        return $this->getTransitionContent($statefulContract, $transitionKey);
    }

    public function getRedirectAfterTransition(StatefulContract $statefulContract, string $transitionKey): ?string
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

    public function getAsyncModalUrl(string $transitionKey, StatefulContract $statefulContract): ?string
    {
        if ($transitionKey == 'archive') {
            return app(Registry::class)->findManagerByModel($statefulContract::class)->route('archive_modal', $statefulContract->id);
        }

        return null;
    }

    private function visitableModelHasAnyLinks(StatefulContract $statefulContract): bool
    {
        if (! $statefulContract instanceof Visitable) {
            return false;
        }

        return $statefulContract->urls->isEmpty();
    }

    private function visitableModelHasAnyOnlineLinks(StatefulContract $statefulContract): bool
    {
        if (! $statefulContract instanceof Visitable) {
            return true;
        }

        foreach ($statefulContract->urls as $url) {
            if ($url->status == LinkStatus::online->value) {
                return true;
            }
        }

        return false;
    }
}
