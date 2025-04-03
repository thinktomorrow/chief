<?php

namespace Thinktomorrow\Chief\Tests\Unit\Resource\Locale;

use Thinktomorrow\Chief\Sites\HasSiteLocales;
use Thinktomorrow\Chief\Sites\LocaleRepository;

class LocaleRepositoryStub implements LocaleRepository
{
    public function saveLocales(HasSiteLocales $model, array $locales): void
    {
        $model->locales = $locales;
        $model->save();
    }
}
