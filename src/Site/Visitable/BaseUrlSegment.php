<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Visitable;

use Thinktomorrow\Chief\Urls\Models\HomepageSlug;

class BaseUrlSegment
{
    public static function find(array $segments, ?string $locale = null)
    {
        if (count($segments) < 1) {
            return '/';
        }
        if (count($segments) == 1) {
            return reset($segments);
        }

        // Localized value
        if (($key = $locale ?? app()->getlocale()) && isset($segments[$key])) {
            return $segments[$key];
        }

        // Fallback localized value
        if (($fallback_locale = config('app.fallback_locale')) && isset($segments[$fallback_locale])) {
            return $segments[$fallback_locale];
        }

        // Fallback to first entry
        return reset($segments);
    }

    public static function prepend(Visitable $model, string $slug, string $site): string
    {
        $slugWithBaseSegment = $model->baseUrlSegment($site).'/'.$slug;
        $slugWithBaseSegment = trim($slugWithBaseSegment, '/');

        // If slug with base segment is empty string, it means that the passed slug was probably a "/" character.
        // so we'll want to return it in case the base segment is not added.
        return $slugWithBaseSegment ?: '/';
    }

    public static function strip(string $slug, string $baseUrlSegment): string
    {
        // If this is a '/' slug, it indicates the homepage for this locale. In this case,
        // we wont be trimming the slash
        if (HomepageSlug::is($slug)) {
            return $slug;
        }

        if ($baseUrlSegment && strpos($slug, $baseUrlSegment) === 0) {
            return trim(substr($slug, strlen($baseUrlSegment)), '/');
        }

        return $slug;
    }
}
