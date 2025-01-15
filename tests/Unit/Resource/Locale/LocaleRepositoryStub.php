<?php

namespace Thinktomorrow\Chief\Tests\Unit\Resource\Locale;

use Thinktomorrow\Chief\Sites\LocaleRepository;
use Thinktomorrow\Chief\Sites\BelongsToSites;

class LocaleRepositoryStub implements LocaleRepository
{
    public function saveLocales(BelongsToSites $model, array $locales): void
    {
        $model->locales = $locales;
        $model->save();
    }
}
