<?php

namespace Thinktomorrow\Chief\Plugins\Seo\Models;

interface HasAlt
{
    public function getAlt(?string $locale = null): string;
}
