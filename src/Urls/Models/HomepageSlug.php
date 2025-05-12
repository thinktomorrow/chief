<?php

namespace Thinktomorrow\Chief\Urls\Models;

class HomepageSlug
{
    public static function is(?string $slug): bool
    {
        return $slug === '' || $slug === '/';
    }
}
