<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\ProvidesUrl;

class BaseUrlSegment
{
    public static function find(array $segments, string $locale = null)
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

    /**
     * @param ProvidesUrl $model
     * @param string $slug
     * @param $locale
     * @return string
     */
    public static function prepend(ProvidesUrl $model, string $slug, $locale): string
    {
        $slugWithBaseSegment = $model->baseUrlSegment($locale) . '/' . $slug;
        $slugWithBaseSegment = trim($slugWithBaseSegment, '/');

        // If slug with base segment is empty string, it means that the passed slug was probably a "/" character.
        // so we'll want to return it in case the base segment is not added.
        return $slugWithBaseSegment ?: '/';
    }

    public static function strip($value)
    {
        $originalValue = $value = trim($value, '/');

        $segments = static::all();

        foreach ($segments as $segment) {
            if (0 === strpos($originalValue, $segment)) {
                $value = substr($value, strlen($segment));
            }
        }

        return trim($value, '/');
    }

    private static function all(): array
    {
        $segments = [];

        // TODO: fix this and also test this.
        $managers = app(Managers::class)->all();

        foreach ($managers as $manager) {
            if (contract($manager->modelInstance(), ProvidesUrl::class)) {
                $segments[] = $manager->modelInstance()->baseUrlSegment();
            }
        }

        return array_unique($segments);
    }
}
