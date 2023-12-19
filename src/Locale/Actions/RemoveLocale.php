<?php

namespace Thinktomorrow\Chief\Locale\Actions;

use Thinktomorrow\Chief\Locale\Events\LocalesUpdated;
use Thinktomorrow\Chief\Locale\LocaleRepository;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class RemoveLocale
{
    public function handle(LocaleRepository $resource, ReferableModel $model, array $locales): void
    {
        $previousState = $resource->getLocales($model);
        $remaining = $previousState;

        foreach ($remaining as $key => $locale) {
            if (in_array($locale, $locales)) {
                unset($remaining[$key]);
            }
        }

        $resource->saveLocales($model, array_values($remaining));

        event(new LocalesUpdated($model->modelReference(), $resource->getLocales($model), $previousState));
    }
}
