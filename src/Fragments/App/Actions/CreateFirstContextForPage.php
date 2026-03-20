<?php

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\CreateContext;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSites;

class CreateFirstContextForPage
{
    private ContextApplication $contextApplication;

    private ContextRepository $contextRepository;

    public function __construct(ContextApplication $contextApplication, ContextRepository $contextRepository)
    {
        $this->contextApplication = $contextApplication;
        $this->contextRepository = $contextRepository;
    }

    public function onManagedModelCreated(ManagedModelCreated $event): void
    {
        $model = $event->modelReference->instance();

        if (! $model instanceof ContextOwner || ! $model instanceof ReferableModel) {
            return;
        }

        $this->handle($model);
    }

    public function handle(ContextOwner&ReferableModel $model): void
    {
        if (! $model->allowContexts()) {
            return;
        }

        if ($this->contextRepository->countContexts($model->modelReference()) > 0) {
            return;
        }

        $sites = $model instanceof HasAllowedSites
            ? ChiefSites::verifiedLocales($model->getAllowedSites())
            : ChiefSites::locales();

        $this->contextApplication->create(new CreateContext(
            $model->modelReference(),
            $sites,
            $sites,
            'Inhoud'
        ));
    }
}
