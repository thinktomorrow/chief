<?php

namespace Thinktomorrow\Chief\Locale\Actions;

use Thinktomorrow\Chief\Locale\Events\LocalesUpdated;
use Thinktomorrow\Chief\Locale\LocaleRepository;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class AddLocale
{
    public function handle(LocaleRepository $resource, ReferableModel $model, array $locales): void
    {
        $previousState = $resource->getLocales($model);

        $resource->saveLocales($model, array_merge($resource->getLocales($model), $locales));

        event(new LocalesUpdated($model->modelReference(), $resource->getLocales($model), $previousState));
    }
}
