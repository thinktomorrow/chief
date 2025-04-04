<?php

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\CreateContext;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\Sites\ChiefSites;

class CreateFirstContextForPage
{
    private ContextApplication $contextApplication;

    public function __construct(ContextApplication $contextApplication)
    {

        $this->contextApplication = $contextApplication;
    }

    public function onManagedModelCreated(ManagedModelCreated $event): void
    {
        $model = $event->modelReference->instance();

        if (! $model instanceof ContextOwner) {
            return;
        }

        $this->contextApplication->create(new CreateContext(
            $event->modelReference,
            ChiefSites::locales(),
            'Inhoud'
        ));

    }
}
