<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\PageState;

use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelArchived;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelPublished;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelQueuedForDeletion;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUnPublished;
use Thinktomorrow\Chief\ManagedModels\States\State\StateAdminConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StateAdminConfigDefaults;
use Thinktomorrow\Chief\ManagedModels\States\State\StateConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Site\Urls\Form\LinkForm;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class PageStateConfig implements StateConfig, StateAdminConfig
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
        if ('publish' == $transition) {
            event(new ManagedModelPublished($statefulContract->modelReference()));
            Audit::activity()->performedOn($statefulContract)->log('published');
        }

        if ('unpublish' == $transition) {
            event(new ManagedModelUnPublished($statefulContract->modelReference()));
            Audit::activity()->performedOn($statefulContract)->log('unpublished');
        }

        if ('archive' == $transition) {
            event(
                new ManagedModelArchived(
                    $statefulContract->modelReference(),
                    isset($data['redirect_id']) ? ModelReference::fromString($data['redirect_id']) : null
                )
            );

            Audit::activity()->performedOn($statefulContract)->log('archived');
        }

        if ('unarchive' == $transition) {
            Audit::activity()->performedOn($statefulContract)->log('unarchived');
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

    public function getWindowContent(StatefulContract $statefulContract, array $viewData): string
    {
        return view('chief::manager.windows.state.pagestate-window-content', $viewData)->render();
    }

    public function getStateLabel(StatefulContract $statefulContract): ?string
    {
        if ($statefulContract instanceof Visitable) {
            if ($statefulContract->inOnlineState()) {
                if (LinkForm::fromModel($statefulContract)->isAnyLinkOnline()) {
                    return '<span class="label label-xs label-success">Online</span>';
                } else {
                    return '<span class="label label-xs label-warning">Gepubliceerd maar ontbreekt nog een link.</span>';
                }
            }

            switch ($statefulContract->getState($this->getStateKey())) {
                case PageState::draft:
                    return '<span class="label label-xs label-error">Offline</span>';
            }
        }

        switch ($statefulContract->getState($this->getStateKey())) {

            case PageState::published:
                return '<span class="label label-xs label-success">Gepubliceerd</span>';

            case PageState::draft:
                return '<span class="label label-xs label-error">In draft</span>';

            case PageState::archived:
                return '<span class="label label-xs label-grey">Gearchiveerd</span>';

            case PageState::deleted:
                return '<span class="label label-xs label-grey">Verwijderd</span>';

            default:
                return $statefulContract->getState($this->getStateKey())->getValueAsString();
        }
    }

    public function getEditContent(StatefulContract $statefulContract): ?string
    {
        $state = $statefulContract->getState($this->getStateKey());

        if (PageState::draft == $state) {
            return '<p>De pagina staat nog in draft.</p>';
        }

        if (PageState::published == $state) {
            return '<p>De pagina staat online. 👍</p>';
        }

        return null;
    }

    public function getTransitionButtonLabel(string $transitionKey): ?string
    {
        switch ($transitionKey) {
            case 'publish':
                return 'Publiceer deze pagina';

            case 'unpublish':
                return 'Haal offline';

            case 'archive':
                return 'archiveer';

            case 'unarchive':
                return 'Haal uit archief';

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

            case 'archive':
                return 'warning';

            case 'delete':
                return 'error';

            default:
                return 'info';
        }
    }

    public function getTransitionContent(string $transitionKey): ?string
    {
        if ($transitionKey == 'delete') {
            return 'Opgelet! Het verwijderen van een pagina is definitief en kan niet worden ongedaan gemaakt.';
        }

        return null;
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
        if ('publish' == $transitionKey) {
            return 'De pagina is online geplaatst.';
        }

        if ('publish' == $transitionKey) {
            return 'De pagina is offline gehaald.';
        }

        if ('archive' == $transitionKey) {
            return 'De pagina is gearchiveerd.';
        }

        if ('unarchive' == $transitionKey) {
            return 'De pagina is uit het archief gehaald.';
        }

        if ('delete' == $transitionKey) {
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
}
