<?php

namespace Thinktomorrow\Chief\Tests\Unit\Resource\Locale;

use Thinktomorrow\Chief\Sites\HasAllowedSites;
use Thinktomorrow\Chief\Sites\LocaleRepository;

class LocaleRepositoryStub implements LocaleRepository
{
    public function saveLocales(HasAllowedSites $model, array $locales): void
    {
        $model->locales = $locales;
        $model->save();
    }
}
