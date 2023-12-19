<?php

namespace Thinktomorrow\Chief\Tests\Unit\Resource\Locale;

use Thinktomorrow\Chief\Locale\LocaleRepository;
use Thinktomorrow\Chief\Locale\Localisable;

class LocaleRepositoryStub implements LocaleRepository
{
    public function saveLocales(Localisable $model, array $locales): void
    {
        $model->locales = $locales;
        $model->save();
    }
}
