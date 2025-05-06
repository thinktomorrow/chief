<?php

namespace Thinktomorrow\Chief\Sites\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Sites\HasAllowedSites;
use Thinktomorrow\Chief\Sites\HasAllowedSitesDefaults;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

class LocalizedFixture extends Model implements HasAllowedSites
{
    use HasAllowedSitesDefaults;
    use HasDynamicAttributes;

    public $dynamicKeys = [
        'title',
    ];

    public function checkFallBackLocaleFor(string $locale): ?string
    {
        return $this->getDynamicFallbackLocale($locale);
    }
}
