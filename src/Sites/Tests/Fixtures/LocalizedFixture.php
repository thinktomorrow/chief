<?php

namespace Thinktomorrow\Chief\Sites\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Sites\Locales\Localized;
use Thinktomorrow\Chief\Sites\Locales\LocalizedDefaults;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

class LocalizedFixture extends Model implements Localized
{
    use HasDynamicAttributes;
    use LocalizedDefaults;

    public $dynamicKeys = [
        'title',
    ];

    public function checkFallBackLocaleFor(string $locale): ?string
    {
        return $this->getDynamicFallbackLocale($locale);
    }
}
