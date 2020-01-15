<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\ProvidesUrl;

use Thinktomorrow\Chief\Management\Managers;

class BaseUrlSegment
{
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

        $managers = app(Managers::class)->all();

        foreach ($managers as $manager) {
            if (contract($manager->existingModel(), ProvidesUrl::class)) {
                $segments[] = $manager->existingModel()->baseUrlSegment();
            }
        }

        return array_unique($segments);
    }
}
