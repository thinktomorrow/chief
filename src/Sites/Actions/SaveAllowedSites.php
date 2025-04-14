<?php

namespace Thinktomorrow\Chief\Sites\Actions;

use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\RemoveActiveSite;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\Events\ModelSitesUpdated;
use Thinktomorrow\Chief\Sites\HasAllowedSites;

class SaveAllowedSites
{
    private ContextApplication $contextApplication;

    public function __construct(ContextApplication $contextApplication)
    {
        $this->contextApplication = $contextApplication;
    }

    public function handle(HasAllowedSites&ReferableModel $model, array $locales): void
    {
        $previousState = $model->getAllowedSites();

        $model->setAllowedSites(array_values(array_unique($locales)));
        $model->save();

        // If the model is a context owner, we must also update context active_sites / enabled_sites
        $removedLocales = array_diff($previousState, $locales);

        if ($model instanceof ContextOwner) {
            foreach ($removedLocales as $removedLocale) {
                $this->contextApplication->removeActiveSite(new RemoveActiveSite($model->modelReference(), $removedLocale));
            }
        }

        event(new ModelSitesUpdated($model->modelReference(), $model->getAllowedSites(), $previousState));
    }
}
