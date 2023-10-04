<?php

namespace Thinktomorrow\Chief\Tests\Unit\Resource\Locale;

use Thinktomorrow\Chief\Locale\LocaleRepository;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class LocaleRepositoryStub implements LocaleRepository
{
    public function saveLocales(ReferableModel $model, array $locales): void
    {
        $model->locales = $locales;
        $model->save();
    }

    public function getLocales(ReferableModel $model): array
    {
        return $model->locales ?: [];
    }
}
