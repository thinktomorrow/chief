<?php

namespace Thinktomorrow\Chief\Sites\Actions;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Sites\HasActiveSites;

/**
 * Makes sure that each site is only active on one model.
 */
class SyncActiveSites
{
    public function handle(HasActiveSites $model, Collection $models): void
    {
        // Test to add to teststuite: by default the first model / menu is used when no active site is set...
        foreach ($model->getActiveSites() as $activeSite) {
            // Presence on other models should be removed
            $models->filter(fn ($m) => $m->hasActiveSite($activeSite))
                ->each(function ($m) use ($activeSite) {
                    $m->removeActiveSite($activeSite);
                    $m->save();
                });
        }
    }
}
