<?php

namespace Thinktomorrow\Chief\Tests\Unit\Resource\Locale;

use Thinktomorrow\Chief\Sites\BelongsToSites;
use Thinktomorrow\Chief\Sites\LocaleRepository;

class LocaleRepositoryStub implements LocaleRepository
{
    public function saveLocales(BelongsToSites $model, array $locales): void
    {
        $model->locales = $locales;
        $model->save();
    }
}
